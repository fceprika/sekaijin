<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix news with is_published = true but published_at = null
        DB::table('news')
            ->where('is_published', true)
            ->whereNull('published_at')
            ->update(['published_at' => now()]);

        // Fix articles with is_published = true but published_at = null
        DB::table('articles')
            ->where('is_published', true)
            ->whereNull('published_at')
            ->update(['published_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot reliably reverse this migration
        // as we don't know which records had null published_at originally
    }
};
