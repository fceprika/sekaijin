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
        Schema::table('news', function (Blueprint $table) {
            // Make country_id nullable for API compatibility
            $table->unsignedBigInteger('country_id')->nullable()->change();
            
            // Set default values for other fields that may cause issues
            $table->string('category')->default('general')->change();
            $table->boolean('is_featured')->default(false)->change();
            $table->boolean('is_published')->default(true)->change();
            $table->integer('views')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Revert changes
            $table->unsignedBigInteger('country_id')->nullable(false)->change();
        });
    }
};
