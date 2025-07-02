<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name_fr' => 'Thaïlande',
                'slug' => 'thailande',
                'emoji' => '🇹🇭',
                'description' => 'Découvrez la communauté française en Thaïlande, entre temples dorés, plages paradisiaques et street food délicieuse.',
            ],
            [
                'name_fr' => 'Japon',
                'slug' => 'japon',
                'emoji' => '🇯🇵',
                'description' => 'Explorez le Japon avec la communauté française : tradition et modernité, sushi et ramen, Tokyo et Kyoto.',
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['slug' => $country['slug']],
                $country
            );
        }
    }
}
