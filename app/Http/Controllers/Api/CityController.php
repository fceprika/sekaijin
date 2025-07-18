<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CityDataService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * City data service.
     */
    private CityDataService $cityDataService;

    /**
     * Constructor.
     */
    public function __construct(CityDataService $cityDataService)
    {
        $this->cityDataService = $cityDataService;
    }

    /**
     * Get cities for a given country using local data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:100',
            'with_coordinates' => 'nullable|boolean',
        ]);

        $country = $request->input('country');
        $withCoordinates = $request->boolean('with_coordinates', false);

        $cities = $this->cityDataService->getCitiesForCountry($country, $withCoordinates);

        return response()->json([
            'success' => true,
            'country' => $country,
            'cities' => $cities,
        ]);
    }

    /**
     * Get list of supported countries (Europe and Asia only).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSupportedCountries()
    {
        $countries = $this->cityDataService->getSupportedCountries();

        return response()->json([
            'success' => true,
            'countries' => $countries,
        ]);
    }

    /**
     * Get coordinates for a specific city in a country.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityCoordinates(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
        ]);

        $country = $request->input('country');
        $city = $request->input('city');

        $coordinates = $this->cityDataService->getCityCoordinates($country, $city);

        if ($coordinates) {
            return response()->json([
                'success' => true,
                'country' => $country,
                'city' => $city,
                'coordinates' => $coordinates,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'City not found',
        ], 404);
    }
}
