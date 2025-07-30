<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageDownloadService
{
    /**
     * Download image from URL and store it locally.
     */
    public function downloadAndStore(string $url, string $directory = 'news_thumbnails'): ?string
    {
        try {
            // If it's a YouTube video URL, convert it to thumbnail URL
            if ($this->isYouTubeVideoUrl($url)) {
                $url = $this->convertYouTubeVideoToThumbnail($url);
                if (! $url) {
                    return null;
                }
            }

            // SECURITY: Validate URL before downloading
            if (! $this->isAllowedUrl($url)) {
                Log::warning('Blocked potentially dangerous URL', ['url' => $url]);

                return null;
            }

            // Try to download the image
            $response = $this->downloadImage($url);

            if (! $response) {
                // If it's a YouTube maxresdefault URL that failed, try hqdefault
                if ($this->isYouTubeMaxresdefaultUrl($url)) {
                    $fallbackUrl = $this->getYouTubeHqdefaultUrl($url);
                    $response = $this->downloadImage($fallbackUrl);
                }

                if (! $response) {
                    return null;
                }
            }

            // Generate unique filename
            $filename = $this->generateUniqueFilename($url);

            // Ensure directory exists
            $fullPath = "public/{$directory}";
            if (! Storage::exists($fullPath)) {
                Storage::makeDirectory($fullPath);
            }

            // Store the image
            $filePath = "{$fullPath}/{$filename}";
            Storage::put($filePath, $response->body());

            return $filename;

        } catch (\Exception $e) {
            Log::error('Failed to download image', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Validate URL to prevent SSRF attacks.
     */
    private function isAllowedUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);

        if (! $parsedUrl || ! isset($parsedUrl['scheme'], $parsedUrl['host'])) {
            return false;
        }

        // Only allow HTTP/HTTPS
        if (! in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }

        $host = strtolower($parsedUrl['host']);

        // Whitelist trusted domains for images
        $allowedDomains = [
            'img.youtube.com',
            'i.ytimg.com',
            'images.unsplash.com',
            'unsplash.com',
            'pixabay.com',
            'pexels.com',
            'cdn.pixabay.com',
            'images.pexels.com',
        ];

        // Check if domain is in whitelist
        foreach ($allowedDomains as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return true;
            }
        }

        // Resolve IP and check for private ranges
        $ip = gethostbyname($host);
        if ($ip === $host) {
            // gethostbyname failed, might be an IP already
            $ip = $host;
        }

        // Block private IP ranges, localhost, etc.
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        // Additional security: block common internal ports
        if (isset($parsedUrl['port'])) {
            $dangerousPorts = [22, 23, 25, 53, 80, 110, 143, 993, 995, 1433, 3306, 5432, 6379, 11211, 27017];
            if (in_array($parsedUrl['port'], $dangerousPorts)) {
                return false;
            }
        }

        return false; // Default deny - only whitelisted domains allowed
    }

    /**
     * Download image from URL.
     */
    private function downloadImage(string $url): ?\Illuminate\Http\Client\Response
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ])
                ->get($url);

            if ($response->successful()) {
                // SECURITY: Enhanced file validation
                if (! $this->isValidImageResponse($response)) {
                    return null;
                }

                return $response;
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('Failed to download image from URL', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Validate image response for security and format compliance.
     */
    private function isValidImageResponse(\Illuminate\Http\Client\Response $response): bool
    {
        // Check file size (10MB limit)
        $contentLength = $response->header('Content-Length');
        if ($contentLength && $contentLength > 10 * 1024 * 1024) {
            Log::warning('Image file too large', ['size' => $contentLength]);

            return false;
        }

        // Validate Content-Type
        $contentType = $response->header('Content-Type');
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

        if (! $contentType || ! in_array(strtolower($contentType), $allowedTypes)) {
            Log::warning('Invalid content type', ['type' => $contentType]);

            return false;
        }

        // Validate actual image content
        try {
            $imageData = $response->body();

            // Check actual file size after download
            if (strlen($imageData) > 10 * 1024 * 1024) {
                Log::warning('Downloaded image too large', ['size' => strlen($imageData)]);

                return false;
            }

            // Verify it's actually an image
            $imageInfo = getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                Log::warning('Invalid image data');

                return false;
            }

            // Check image dimensions (reasonable limits)
            if ($imageInfo[0] > 4000 || $imageInfo[1] > 4000) {
                Log::warning('Image dimensions too large', [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                ]);

                return false;
            }

            // Verify MIME type matches actual content
            $actualMimeType = $imageInfo['mime'] ?? '';
            $expectedMimes = [
                'image/jpeg' => ['image/jpeg', 'image/jpg'],
                'image/jpg' => ['image/jpeg', 'image/jpg'],
                'image/png' => ['image/png'],
                'image/gif' => ['image/gif'],
                'image/webp' => ['image/webp'],
            ];

            $allowedMimes = $expectedMimes[strtolower($contentType)] ?? [];
            if (! in_array($actualMimeType, $allowedMimes)) {
                Log::warning('MIME type mismatch', [
                    'declared' => $contentType,
                    'actual' => $actualMimeType,
                ]);

                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::warning('Image validation failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Check if URL is a YouTube video URL.
     */
    private function isYouTubeVideoUrl(string $url): bool
    {
        return str_contains($url, 'youtube.com/watch?v=') || str_contains($url, 'youtu.be/');
    }

    /**
     * Convert YouTube video URL to thumbnail URL.
     */
    private function convertYouTubeVideoToThumbnail(string $url): ?string
    {
        $videoId = $this->extractYouTubeVideoId($url);

        if (! $videoId) {
            return null;
        }

        // Return maxresdefault URL (will fallback to hqdefault if needed)
        return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
    }

    /**
     * Extract YouTube video ID from various URL formats.
     */
    private function extractYouTubeVideoId(string $url): ?string
    {
        // Pattern for youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern for youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern for youtube.com/embed/VIDEO_ID
        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if URL is a YouTube maxresdefault URL.
     */
    private function isYouTubeMaxresdefaultUrl(string $url): bool
    {
        return str_contains($url, 'img.youtube.com') && str_contains($url, 'maxresdefault.jpg');
    }

    /**
     * Convert maxresdefault URL to hqdefault URL.
     */
    private function getYouTubeHqdefaultUrl(string $url): string
    {
        return str_replace('maxresdefault.jpg', 'hqdefault.jpg', $url);
    }

    /**
     * Generate unique filename for the image.
     */
    private function generateUniqueFilename(string $url): string
    {
        $extension = $this->getImageExtensionFromUrl($url);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get image extension from URL.
     */
    private function getImageExtensionFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Default extensions for common image types
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array(strtolower($extension), $validExtensions)) {
            return strtolower($extension);
        }

        // Default to jpg if extension is not recognized
        return 'jpg';
    }

    /**
     * Delete image file if it exists.
     */
    public function deleteImage(string $filename, string $directory = 'news_thumbnails'): bool
    {
        try {
            $filePath = "public/{$directory}/{$filename}";

            if (Storage::exists($filePath)) {
                return Storage::delete($filePath);
            }

            return true; // File doesn't exist, consider it "deleted"

        } catch (\Exception $e) {
            Log::error('Failed to delete image', [
                'filename' => $filename,
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get the public URL for a stored image.
     */
    public function getImageUrl(string $filename, string $directory = 'news_thumbnails'): string
    {
        return asset("storage/{$directory}/{$filename}");
    }
}
