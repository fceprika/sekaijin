<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExpatController extends Controller
{
    /**
     * Get expatriates count by country for map visualization.
     */
    public function expatsByCountry(): JsonResponse
    {
        // Cache for 10 minutes since country stats don't change often
        $expatsByCountry = Cache::remember('api.expats_by_country', 10 * 60, function () {
            return User::whereNotNull('country_residence')
                ->where('country_residence', '!=', '')
                ->groupBy('country_residence')
                ->selectRaw('country_residence as country, COUNT(*) as count')
                ->orderBy('count', 'DESC')
                ->get();
        });

        return response()->json($expatsByCountry);
    }

    /**
     * Get members with location sharing enabled for individual display on map.
     */
    public function membersWithLocation(Request $request): JsonResponse
    {
        // Add pagination to improve performance
        $limit = min($request->input('limit', 100), 200); // Max 200 members per request
        $offset = $request->input('offset', 0);

        // Cache key based on pagination for efficient caching
        $cacheKey = "api.members_with_location.{$limit}.{$offset}";

        // Cache for 3 minutes (short cache for location data)
        $members = Cache::remember($cacheKey, 3 * 60, function () use ($limit, $offset) {
            return User::where('is_visible_on_map', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with('country:id,name_fr') // Charger la relation country avec le nom français
                ->select([
                    'name',
                    'latitude',
                    'longitude',
                    'city_detected',
                    'city_residence',
                    'country_residence',
                    'country_id',
                    'role',
                    'updated_at',
                ])
                ->offset($offset)
                ->limit($limit)
                ->get()
                ->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'latitude' => (float) $user->latitude,
                        'longitude' => (float) $user->longitude,
                        'location' => $user->getDisplayLocation(),
                        'role' => $user->getRoleDisplayName(),
                        'profile_url' => route('public.profile', $user->getSlug()),
                        'updated_at' => $user->updated_at?->format('Y-m-d H:i:s'),
                    ];
                });
        });

        return response()->json([
            'members' => $members,
            'pagination' => [
                'limit' => $limit,
                'offset' => $offset,
                'count' => $members->count(),
            ],
        ]);
    }

    /**
     * Update user location with privacy protection.
     */
    public function updateLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de localisation invalides.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();

            // Enable location sharing if not already enabled
            if (! $user->is_visible_on_map) {
                $user->is_visible_on_map = true;
                $user->save();
            }

            // Update location with privacy protection
            $user->updateLocation(
                $request->input('latitude'),
                $request->input('longitude'),
                $request->input('city')
            );

            // Clear relevant cache when location is updated
            $this->clearLocationCaches();

            Log::info('User location updated', [
                'user_id' => $user->id,
                'city' => $request->input('city'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Localisation mise à jour avec succès.',
                'data' => [
                    'location' => $user->getDisplayLocation(),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating user location', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la localisation.',
            ], 500);
        }
    }

    /**
     * Remove user location from map.
     */
    public function removeLocation(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Clear location data
            $user->update([
                'is_visible_on_map' => false,
                'latitude' => null,
                'longitude' => null,
                'city_detected' => null,
            ]);

            // Clear relevant cache when location is removed
            $this->clearLocationCaches();

            Log::info('User location removed', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Localisation supprimée avec succès.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing user location', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la localisation.',
            ], 500);
        }
    }

    /**
     * Clear location-related caches.
     */
    private function clearLocationCaches(): void
    {
        // Clear specific cache keys
        $cacheKeys = [
            'api.expats_by_country',
            'community.stats',
            'members.recent',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear pagination-based cache keys more efficiently
        $paginationKeys = [
            'api.members_with_location.100.0',
            'api.members_with_location.200.0',
            'api.members_with_location.100.100',
            'api.members_with_location.200.100',
            'api.members_with_location.100.200',
            'api.members_with_location.200.200',
            'api.members_with_location.100.300',
            'api.members_with_location.200.300',
            'api.members_with_location.100.400',
            'api.members_with_location.200.400',
            'api.members_with_location.100.500',
            'api.members_with_location.200.500',
        ];

        foreach ($paginationKeys as $key) {
            Cache::forget($key);
        }
    }
}
