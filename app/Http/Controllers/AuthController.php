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
        // Validation pour l'étape 1 : création de compte
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'unique:users,name_slug',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'not_regex:/^[._-]/',
                'not_regex:/[._-]$/',
                function ($attribute, $value, $fail) {
                    $slug = \App\Models\User::generateSlug($value);
                    if (\App\Models\User::where('name_slug', $slug)->exists()) {
                        $fail('Ce pseudo génère un identifiant déjà utilisé. Veuillez en choisir un autre.');
                    }
                },
            ],
            'email' => 'required|string|email|max:255|unique:users',
            'country_interest' => 'required|string|in:Thaïlande',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&\-_]+$/',
            ],
            'terms' => 'required|accepted'
        ], [
            'name.regex' => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'name.not_regex' => 'Le pseudo ne peut pas commencer ou finir par un point, tiret ou underscore.',
            'password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
            'country_interest.required' => 'Veuillez sélectionner un pays d\'intérêt.',
            'country_interest.in' => 'Seule la Thaïlande est disponible pour le moment.'
        ]);

        if ($validator->fails()) {
            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Créer le compte utilisateur (étape 1)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'country_interest' => $request->country_interest,
            'password' => Hash::make($request->password),
        ]);

        // Connecter l'utilisateur immédiatement
        Auth::login($user);

        // Retourner une réponse JSON pour l'étape 2
        return response()->json([
            'success' => true,
            'message' => 'Compte créé avec succès !',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'country_interest' => $user->country_interest
            ]
        ]);
    }

    public function enrichProfile(Request $request)
    {
        // Validation pour l'étape 2 : enrichissement du profil
        $validator = Validator::make($request->all(), [
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:100|mimetypes:image/jpeg,image/png,image/webp',
            'country_residence' => 'nullable|string|max:255',
            'city_residence' => 'nullable|string|max:255',
            'share_location' => 'nullable|boolean',
            'initial_latitude' => 'nullable|numeric|between:-90,90',
            'initial_longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'avatar doit être au format JPEG, JPG, PNG ou WebP.',
            'avatar.max' => 'L\'avatar ne doit pas dépasser 100KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Gérer l'upload de l'avatar
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = $this->uploadAvatar($request->file('avatar'));
            } catch (\InvalidArgumentException $e) {
                return response()->json([
                    'success' => false,
                    'errors' => ['avatar' => [$e->getMessage()]]
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors' => ['avatar' => ['Erreur lors du traitement de l\'image.']]
                ], 422);
            }
        }

        // Mettre à jour les informations du profil
        $user->update([
            'avatar' => $avatarPath,
            'country_residence' => $request->country_residence,
            'city_residence' => $request->city_residence,
            'is_visible_on_map' => $request->boolean('share_location', false),
        ]);

        // Gérer la géolocalisation si demandée
        if ($request->filled('initial_latitude') && $request->filled('initial_longitude') && $user->is_visible_on_map) {
            try {
                $latitude = $request->input('initial_latitude');
                $longitude = $request->input('initial_longitude');
                
                $city = $this->getCityFromCoordinates($latitude, $longitude);
                $user->updateLocation($latitude, $longitude, $city);
                
                \Log::info('User location updated during profile enrichment', [
                    'user_id' => $user->id,
                    'city' => $city
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to set location during profile enrichment', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil enrichi avec succès !',
            'redirect_url' => '/'
        ]);
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
     * Check username availability for real-time validation
     */
    public function checkUsername($username)
    {
        try {
            // Clean the username
            $cleanedUsername = trim($username);
            
            // Validate format
            if (empty($cleanedUsername) || strlen($cleanedUsername) < 3) {
                return response()->json([
                    'available' => false,
                    'message' => 'Le pseudo doit contenir au moins 3 caractères.'
                ]);
            }
            
            // Check if username matches regex requirements
            if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $cleanedUsername)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.'
                ]);
            }
            
            // Check if starts or ends with special characters
            if (preg_match('/^[._-]/', $cleanedUsername) || preg_match('/[._-]$/', $cleanedUsername)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Le pseudo ne peut pas commencer ou finir par un point, tiret ou underscore.'
                ]);
            }
            
            // Check if username exists (case-insensitive)
            $exists = User::where('name', 'LIKE', $cleanedUsername)->exists();
            
            // Also check if the generated slug would conflict
            $slug = User::generateSlug($cleanedUsername);
            $slugExists = User::where('name_slug', $slug)->exists();
            
            if ($exists || $slugExists) {
                return response()->json([
                    'available' => false,
                    'message' => 'Ce pseudo est déjà pris.'
                ]);
            }
            
            return response()->json([
                'available' => true,
                'message' => 'Ce pseudo est disponible.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Username check error', [
                'username' => $username,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'available' => false,
                'message' => 'Erreur lors de la vérification.'
            ]);
        }
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

    /**
     * Upload and optimize avatar image
     */
    private function uploadAvatar($file): string
    {
        // Additional security validation
        $this->validateImageFile($file);
        
        // Generate unique filename with proper extension
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Ensure avatars directory exists
        $avatarPath = public_path('storage/avatars');
        if (!file_exists($avatarPath)) {
            mkdir($avatarPath, 0755, true);
        }
        
        // Move the file
        $file->move($avatarPath, $filename);
        
        return $filename;
    }
    
    /**
     * Validate image file content and headers
     */
    private function validateImageFile($file): void
    {
        // Check file size (double-check on server side)
        if ($file->getSize() > 100 * 1024) {
            throw new \InvalidArgumentException('Le fichier est trop volumineux. Maximum 100KB autorisé.');
        }
        
        // Validate MIME type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Type de fichier non autorisé. Seuls JPEG, PNG et WebP sont acceptés.');
        }
        
        // Validate file headers (magic bytes)
        $fileContent = file_get_contents($file->getPathname());
        $validHeaders = [
            'jpeg' => ["\xFF\xD8\xFF"],
            'png' => ["\x89\x50\x4E\x47\x0D\x0A\x1A\x0A"],
            'webp' => ["RIFF", "WEBP"]
        ];
        
        $isValidHeader = false;
        foreach ($validHeaders as $type => $headers) {
            foreach ($headers as $header) {
                if (strpos($fileContent, $header) === 0 || 
                    ($type === 'webp' && strpos($fileContent, 'RIFF') === 0 && strpos($fileContent, 'WEBP') !== false)) {
                    $isValidHeader = true;
                    break 2;
                }
            }
        }
        
        if (!$isValidHeader) {
            throw new \InvalidArgumentException('Fichier image corrompu ou invalide.');
        }
    }
}
