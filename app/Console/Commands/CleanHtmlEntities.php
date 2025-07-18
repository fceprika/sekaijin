<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\News;
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

                // Multiple decode passes and manual replacements for stubborn entities
                $news->title = $this->cleanHtmlEntities($news->title);
                $news->excerpt = $this->cleanHtmlEntities($news->excerpt);
                $news->content = $this->cleanHtmlEntities($news->content);

                if ($originalTitle !== $news->title || $originalExcerpt !== $news->excerpt || $originalContent !== $news->content) {
                    $news->save();
                    $newsCount++;
                    $this->line("Cleaned news: {$news->title}");
                    if ($originalContent !== $news->content) {
                        $this->line('  Content changed: ' . substr($originalContent, 0, 100) . '...');
                    }
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

                // Multiple decode passes and manual replacements for stubborn entities
                $article->title = $this->cleanHtmlEntities($article->title);
                $article->excerpt = $this->cleanHtmlEntities($article->excerpt);
                $article->content = $this->cleanHtmlEntities($article->content);

                if ($originalTitle !== $article->title || $originalExcerpt !== $article->excerpt || $originalContent !== $article->content) {
                    $article->save();
                    $articleCount++;
                    $this->line("Cleaned article: {$article->title}");
                    if ($originalContent !== $article->content) {
                        $this->line('  Content changed: ' . substr($originalContent, 0, 100) . '...');
                    }
                }
            }
        });

        $this->info("Cleaned {$newsCount} news items and {$articleCount} articles.");
        $this->info('HTML entities cleanup completed!');

        return 0;
    }

    /**
     * Clean HTML entities with multiple approaches.
     */
    private function cleanHtmlEntities($text)
    {
        if (empty($text)) {
            return $text;
        }

        // First pass: Standard HTML entity decoding
        $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Second pass: Manual replacement of common stubborn entities
        $replacements = [
            '&rsquo;' => "'",
            '&lsquo;' => "'",
            '&ldquo;' => '"',
            '&rdquo;' => '"',
            '&nbsp;' => ' ',
            '&amp;' => '&',
            '&lt;' => '<',
            '&gt;' => '>',
            '&quot;' => '"',
            '&#39;' => "'",
            '&#8217;' => "'",
            '&#8216;' => "'",
            '&#8220;' => '"',
            '&#8221;' => '"',
            '&#160;' => ' ',
        ];

        foreach ($replacements as $entity => $replacement) {
            $cleaned = str_replace($entity, $replacement, $cleaned);
        }

        // Third pass: Another round of HTML entity decoding
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $cleaned;
    }
}
