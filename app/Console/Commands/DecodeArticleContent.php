<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\News;

class DecodeArticleContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:decode-url-encoding {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decode URL-encoded content in articles and news';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }
        
        $this->info('Checking articles for URL-encoded content...');
        $this->processArticles($dryRun);
        
        $this->info('Checking news for URL-encoded content...');
        $this->processNews($dryRun);
        
        $this->info('Content decoding complete!');
    }
    
    private function processArticles($dryRun)
    {
        $articles = Article::whereRaw("content LIKE '%_token%' OR content LIKE '%20%' OR content LIKE '%2C%' OR content LIKE '%3D%'")->get();
        
        $this->info("Found {$articles->count()} articles with potentially URL-encoded content");
        
        foreach ($articles as $article) {
            $originalContent = $article->content;
            
            // Try to decode the content
            $decodedContent = $this->decodeContent($originalContent);
            
            if ($decodedContent !== $originalContent) {
                $this->line("Article ID {$article->id}: {$article->title}");
                
                if ($dryRun) {
                    $this->line("  Would decode content (length: " . strlen($originalContent) . " -> " . strlen($decodedContent) . ")");
                } else {
                    $article->update(['content' => $decodedContent]);
                    $this->line("  ✓ Content decoded");
                }
            }
        }
    }
    
    private function processNews($dryRun)
    {
        $news = News::whereRaw("content LIKE '%_token%' OR content LIKE '%20%' OR content LIKE '%2C%' OR content LIKE '%3D%'")->get();
        
        $this->info("Found {$news->count()} news items with potentially URL-encoded content");
        
        foreach ($news as $newsItem) {
            $originalContent = $newsItem->content;
            
            // Try to decode the content
            $decodedContent = $this->decodeContent($originalContent);
            
            if ($decodedContent !== $originalContent) {
                $this->line("News ID {$newsItem->id}: {$newsItem->title}");
                
                if ($dryRun) {
                    $this->line("  Would decode content (length: " . strlen($originalContent) . " -> " . strlen($decodedContent) . ")");
                } else {
                    $newsItem->update(['content' => $decodedContent]);
                    $this->line("  ✓ Content decoded");
                }
            }
        }
    }
    
    private function decodeContent($content)
    {
        // Check if content looks URL-encoded
        if (strpos($content, '%') !== false || strpos($content, '_token=') !== false) {
            try {
                // If it starts with form data, extract just the content part
                if (strpos($content, '_token=') !== false) {
                    // Parse as query string to extract content
                    parse_str($content, $parsed);
                    if (isset($parsed['content'])) {
                        return $parsed['content'];
                    }
                }
                
                // Try URL decoding
                $decoded = urldecode($content);
                
                // Verify the decoded content makes sense (contains HTML or readable text)
                if (strlen($decoded) > 0 && strlen($decoded) < strlen($content)) {
                    return $decoded;
                }
            } catch (\Exception $e) {
                // If decoding fails, return original content
                return $content;
            }
        }
        
        return $content;
    }
}