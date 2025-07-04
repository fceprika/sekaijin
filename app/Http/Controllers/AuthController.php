<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'country_residence' => 'required|string|max:255',
            'destination_country' => [
                'nullable', 
                'string', 
                'max:255',
                'required_if:country_residence,France',
                'different:country_residence'
            ],
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
            'share_location' => 'nullable|boolean',
            'initial_latitude' => 'nullable|numeric|between:-90,90',
            'initial_longitude' => 'nullable|numeric|between:-180,180',
            'initial_city' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'country_residence' => $request->country_residence,
            'destination_country' => $request->destination_country,
            'password' => Hash::make($request->password),
            'is_visible_on_map' => $request->boolean('share_location', false),
        ]);

        Auth::login($user);

        // Si des coordonnées initiales sont fournies, les traiter
        if ($request->filled('initial_latitude') && $request->filled('initial_longitude') && $user->is_visible_on_map) {
            try {
                // Utiliser une API de géocodage inversé simple pour obtenir la ville
                $latitude = $request->input('initial_latitude');
                $longitude = $request->input('initial_longitude');
                
                $city = $this->getCityFromCoordinates($latitude, $longitude);
                
                // Mettre à jour la localisation avec protection de la vie privée
                $user->updateLocation($latitude, $longitude, $city);
                
                \Log::info('User registered with location', [
                    'user_id' => $user->id,
                    'city' => $city
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to set initial location for user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect('/')->with('success', 'Bienvenue dans la communauté Sekaijin !');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Update last login
            Auth::user()->update(['last_login' => now()]);

            return redirect()->intended('/')->with('success', 'Bon retour sur Sekaijin !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Get city name from coordinates using reverse geocoding with caching
     */
    private function getCityFromCoordinates(float $latitude, float $longitude): string
    {
        try {
            // Create cache key based on rounded coordinates (for privacy and performance)
            $roundedLat = round($latitude, 2);
            $roundedLng = round($longitude, 2);
            $cacheKey = "geocoding_{$roundedLat}_{$roundedLng}";
            
            // Check cache first (5 minute cache)
            if (\Cache::has($cacheKey)) {
                return \Cache::get($cacheKey);
            }
            
            // Use a free geocoding service (Nominatim OpenStreetMap)
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10&addressdetails=1";
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Sekaijin/1.0 (contact@sekaijin.com)'
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if ($response === false) {
                throw new \Exception('Failed to fetch geocoding data');
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['address'])) {
                throw new \Exception('Invalid geocoding response');
            }
            
            // Extract city name from various possible fields
            $address = $data['address'];
            $city = $address['city'] ?? 
                   $address['town'] ?? 
                   $address['village'] ?? 
                   $address['county'] ?? 
                   $address['state'] ?? 
                   'Ville inconnue';

            // Cache the result for 5 minutes
            \Cache::put($cacheKey, $city, 300);

            return $city;
        } catch (\Exception $e) {
            \Log::warning('Failed to get city from coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'error' => $e->getMessage()
            ]);
            return 'Ville inconnue';
        }
    }
}
