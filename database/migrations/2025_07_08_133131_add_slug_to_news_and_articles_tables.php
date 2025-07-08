<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add slug to news table (if not already exists)
        if (!Schema::hasColumn('news', 'slug')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('slug')->unique()->after('title');
            });
        }
        
        // Add slug to articles table (if not already exists)
        if (!Schema::hasColumn('articles', 'slug')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->string('slug')->unique()->after('title');
            });
        }
        
        // Populate slugs for existing records
        $this->populateSlugs();
    }
    
    private function populateSlugs()
    {
        // Populate news slugs
        $news = \App\Models\News::all();
        foreach ($news as $item) {
            $slug = $this->generateSlug($item->title, 'news');
            $item->update(['slug' => $slug]);
        }
        
        // Populate article slugs
        $articles = \App\Models\Article::all();
        foreach ($articles as $item) {
            $slug = $this->generateSlug($item->title, 'articles', $item->id);
            $item->update(['slug' => $slug]);
        }
    }
    
    private function generateSlug($title, $table, $excludeId = null)
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if (empty($baseSlug)) {
            $baseSlug = 'item-' . time();
        }
        
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->slugExists($slug, $table, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function slugExists($slug, $table, $excludeId = null)
    {
        $query = \Illuminate\Support\Facades\DB::table($table)->where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        
        if (Schema::hasColumn('articles', 'slug')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};