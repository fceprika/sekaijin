<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPreviewImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup-preview {--hours=2 : Images older than this many hours will be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up preview images that are older than specified hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);
        $deletedCount = 0;

        $this->info("Cleaning up preview images older than {$hours} hours...");

        // Clean articles preview images
        $articlesFiles = Storage::disk('public')->files('images/articles');
        foreach ($articlesFiles as $file) {
            $filename = basename($file);
            if (str_starts_with($filename, 'preview_')) {
                $fileTime = Storage::disk('public')->lastModified($file);
                if ($fileTime && Carbon::createFromTimestamp($fileTime)->lt($cutoffTime)) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                    $this->line("Deleted: {$filename}");
                }
            }
        }

        // Clean news preview images
        $newsFiles = Storage::disk('public')->files('images/news');
        foreach ($newsFiles as $file) {
            $filename = basename($file);
            if (str_starts_with($filename, 'preview_')) {
                $fileTime = Storage::disk('public')->lastModified($file);
                if ($fileTime && Carbon::createFromTimestamp($fileTime)->lt($cutoffTime)) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                    $this->line("Deleted: {$filename}");
                }
            }
        }

        $this->info("Cleanup completed. Deleted {$deletedCount} preview images.");

        return Command::SUCCESS;
    }
}
