<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\Country;
use Illuminate\Console\Command;

class SyncAnnouncementCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:sync-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise les country_id des annonces existantes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Synchronisation des country_id pour les annonces...');

        $announcements = Announcement::whereNull('country_id')->get();
        $updated = 0;

        foreach ($announcements as $announcement) {
            $country = Country::where('name_fr', $announcement->country)->first();

            if ($country) {
                $announcement->update(['country_id' => $country->id]);
                $updated++;
            } else {
                $this->warn("Pays non trouvé pour l'annonce {$announcement->id}: {$announcement->country}");
            }
        }

        $this->info("Synchronisation terminée. {$updated} annonce(s) mise(s) à jour.");

        return 0;
    }
}
