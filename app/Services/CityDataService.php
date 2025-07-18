<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;

class CityDataService
{
    /**
     * Logger instance.
     */
    private LoggerInterface $logger;

    /**
     * Cache key for the complete cities dataset.
     */
    private const CITIES_CACHE_KEY = 'cities_dataset_complete';

    /**
     * Cache duration in seconds (24 hours).
     */
    private const CACHE_DURATION = 86400;

    /**
     * Constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the complete cities dataset from cache or load it.
     */
    public function getCitiesDataset(): array
    {
        return Cache::remember(self::CITIES_CACHE_KEY, self::CACHE_DURATION, function () {
            return $this->loadCitiesFromFile();
        });
    }

    /**
     * Get cities for a specific country.
     *
     * @param bool $withCoordinates Whether to include coordinates in the response
     */
    public function getCitiesForCountry(string $country, bool $withCoordinates = false): array
    {
        $dataset = $this->getCitiesDataset();

        if (! isset($dataset[$country])) {
            return [];
        }

        if ($withCoordinates) {
            // Return full city data including coordinates
            return $dataset[$country];
        }

        // Extract just the city names from the objects
        return array_map(function ($city) {
            return $city['name'];
        }, $dataset[$country]);
    }

    /**
     * Get coordinates for a specific city in a country.
     */
    public function getCityCoordinates(string $country, string $city): ?array
    {
        $dataset = $this->getCitiesDataset();

        if (! isset($dataset[$country])) {
            return null;
        }

        // Find the city
        foreach ($dataset[$country] as $cityData) {
            if ($cityData['name'] === $city) {
                return [
                    'lat' => $cityData['lat'],
                    'lng' => $cityData['lng'],
                ];
            }
        }

        return null;
    }

    /**
     * Get list of supported countries.
     */
    public function getSupportedCountries(): array
    {
        $dataset = $this->getCitiesDataset();

        return array_keys($dataset);
    }

    /**
     * Clear the cities dataset cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CITIES_CACHE_KEY);
    }

    /**
     * Load cities data from JSON file.
     */
    private function loadCitiesFromFile(): array
    {
        try {
            $jsonPath = database_path('data/cities_data.json');

            if (! file_exists($jsonPath)) {
                $this->logger->warning('Cities data file not found: ' . $jsonPath);

                return [];
            }

            $jsonContent = file_get_contents($jsonPath);
            $citiesData = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->warning('Failed to decode cities JSON: ' . json_last_error_msg());

                return [];
            }

            return $citiesData;

        } catch (\Exception $e) {
            $this->logger->warning('Failed to load cities dataset', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
