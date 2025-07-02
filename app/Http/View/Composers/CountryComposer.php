<?php

namespace App\Http\View\Composers;

use App\Models\Country;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

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

        $view->with('allCountries', $allCountries);
    }
}