<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Article;
use Illuminate\Console\Command;

class CleanHtmlEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:clean-html-entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean HTML entities from news and articles content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning HTML entities from content...');
        
        // Clean News
        $newsCount = 0;
        News::chunk(100, function ($newsItems) use (&$newsCount) {
            foreach ($newsItems as $news) {
                $originalTitle = $news->title;
                $originalExcerpt = $news->excerpt;
                $originalContent = $news->content;
                
                $news->title = html_entity_decode($news->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $news->excerpt = html_entity_decode($news->excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $news->content = html_entity_decode($news->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                if ($originalTitle !== $news->title || $originalExcerpt !== $news->excerpt || $originalContent !== $news->content) {
                    $news->save();
                    $newsCount++;
                    $this->line("Cleaned news: {$news->title}");
                }
            }
        });
        
        // Clean Articles
        $articleCount = 0;
        Article::chunk(100, function ($articles) use (&$articleCount) {
            foreach ($articles as $article) {
                $originalTitle = $article->title;
                $originalExcerpt = $article->excerpt;
                $originalContent = $article->content;
                
                $article->title = html_entity_decode($article->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $article->excerpt = html_entity_decode($article->excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $article->content = html_entity_decode($article->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                if ($originalTitle !== $article->title || $originalExcerpt !== $article->excerpt || $originalContent !== $article->content) {
                    $article->save();
                    $articleCount++;
                    $this->line("Cleaned article: {$article->title}");
                }
            }
        });
        
        $this->info("Cleaned {$newsCount} news items and {$articleCount} articles.");
        $this->info('HTML entities cleanup completed!');
        
        return 0;
    }
}
