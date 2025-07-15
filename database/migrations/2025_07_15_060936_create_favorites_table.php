<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('favoritable_type'); // 'App\Models\Article' ou 'App\Models\News'
            $table->unsignedBigInteger('favoritable_id'); // ID de l'article ou actualité
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['favoritable_type', 'favoritable_id']);
            $table->index(['user_id', 'favoritable_type']);
            
            // Éviter les doublons
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
