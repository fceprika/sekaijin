<?php

namespace App\Http\View\Composers;

use App\Services\CommunityStatsService;
use Illuminate\View\View;

class StatsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $stats = CommunityStatsService::getCommunityStats();
        
        $view->with([
            'totalMembers' => number_format($stats['totalMembers']) . '+',
            'totalCountries' => $stats['countriesCovered'] . '+',
            'totalContent' => $stats['totalContent'],
            'thailandMembers' => $stats['thailandMembers'],
        ]);
    }
}