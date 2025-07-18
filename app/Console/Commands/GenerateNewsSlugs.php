<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateNewsSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SEO-friendly slugs for existing news items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating slugs for news items...');

        $newsItems = News::whereNull('slug')->orWhere('slug', '')->get();
        $count = 0;

        foreach ($newsItems as $news) {
            $baseSlug = Str::slug($news->title);
            $slug = $baseSlug;
            $counter = 1;

            // Ensure unique slug
            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $news->update(['slug' => $slug]);
            $count++;

            $this->line("Generated slug for: {$news->title} -> {$slug}");
        }

        $this->info("Generated {$count} slugs successfully!");

        return 0;
    }
}
