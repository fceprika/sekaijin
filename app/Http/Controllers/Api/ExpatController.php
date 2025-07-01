<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ExpatController extends Controller
{
    /**
     * Get expatriates count by country for map visualization
     */
    public function expatsByCountry(): JsonResponse
    {
        $expatsByCountry = User::whereNotNull('country_residence')
            ->where('country_residence', '!=', '')
            ->groupBy('country_residence')
            ->selectRaw('country_residence as country, COUNT(*) as count')
            ->orderBy('count', 'DESC')
            ->get();

        return response()->json($expatsByCountry);
    }
}
