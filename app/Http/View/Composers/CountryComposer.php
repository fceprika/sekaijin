<?php

namespace App\Http\View\Composers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CountryComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Cache countries list for 1 hour to avoid repeated DB queries
        $allCountries = Cache::remember('all_countries', 3600, function () {
            return Country::orderBy('name_fr')->get();
        });

        // Cache site statistics for footer
        $totalMembers = Cache::remember('total_members_count', 3600, function () {
            $count = User::count();
            if ($count >= 1000) {
                return number_format($count / 1000, 0) . 'K+';
            }

            return $count;
        });

        $totalCountries = Cache::remember('total_countries_with_members', 3600, function () {
            $count = User::whereNotNull('country_residence')
                ->distinct('country_residence')
                ->count('country_residence');

            return $count . '+';
        });

        $view->with([
            'allCountries' => $allCountries,
            'totalMembers' => $totalMembers,
            'totalCountries' => $totalCountries,
        ]);
    }
}
