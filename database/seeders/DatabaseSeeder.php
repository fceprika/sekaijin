<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always seed countries as they are required for the application to work
        $this->call([
            CountrySeeder::class,
        ]);

        // Seed content only if we're not in testing environment
        if (! app()->environment('testing')) {
            $this->call([
                ContentSeeder::class,
            ]);
        }
    }
}
