<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter la Chine si elle n'existe pas déjà
        if (! Country::where('slug', 'chine')->exists()) {
            Country::create([
                'name_fr' => 'Chine',
                'slug' => 'chine',
                'emoji' => '🇨🇳',
                'description' => 'République populaire de Chine - Découvrez la vie d\'expatrié en Chine',
            ]);
        }

        // Vérifier que le Vietnam existe (il devrait déjà être là)
        if (! Country::where('slug', 'vietnam')->exists()) {
            Country::create([
                'name_fr' => 'Vietnam',
                'slug' => 'vietnam',
                'emoji' => '🇻🇳',
                'description' => 'République socialiste du Vietnam - Découvrez la vie d\'expatrié au Vietnam',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la Chine et le Vietnam ajoutés par cette migration
        Country::whereIn('slug', ['chine', 'vietnam'])->delete();
    }
};
