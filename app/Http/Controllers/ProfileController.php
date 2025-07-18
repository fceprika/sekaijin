<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Déterminer si on utilise le mode automatique ou manuel AVANT la validation
        $useAutoMode = $request->filled('country_residence_auto') && $request->filled('city_residence_auto');
        
        // Préparer les données pour la validation
        $validationData = $request->all();
        
        // Si mode automatique, utiliser les données auto pour la validation
        if ($useAutoMode) {
            $validationData['country_residence'] = $request->country_residence_auto;
            $validationData['city_residence'] = $request->city_residence_auto;
        } else {
            // En mode manuel, s'assurer que les champs auto sont vides pour la validation
            $validationData['country_residence_auto'] = null;
            $validationData['city_residence_auto'] = null;
        }

        $validator = Validator::make($validationData, [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:users,name,' . $user->id,
                'unique:users,name_slug,' . $user->id,
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'not_regex:/^[._-]/',
                'not_regex:/[._-]$/',
                function ($attribute, $value, $fail) use ($user) {
                    $slug = \App\Models\User::generateSlug($value);
                    if (\App\Models\User::where('name_slug', $slug)->where('id', '!=', $user->id)->exists()) {
                        $fail('Ce pseudo génère un identifiant déjà utilisé. Veuillez en choisir un autre.');
                    }
                },
            ],
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:100|mimetypes:image/jpeg,image/png,image/webp',
            'remove_avatar' => 'nullable|boolean',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'country_residence' => 'nullable|string|max:255',
            'destination_country' => [
                'nullable', 
                'string', 
                'max:255',
                'required_if:country_residence,France',
                'different:country_residence'
            ],
            'city_residence' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'youtube_username' => 'nullable|string|max:255|regex:/^@[a-zA-Z0-9_.-]+$/',
            'instagram_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_.]+$/',
            'tiktok_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_.]+$/',
            'linkedin_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\-_.]+$/',
            'twitter_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_]+$/',
            'facebook_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9._-]+$/',
            'telegram_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_]+$/',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => [
                'nullable',
                'string',
                'min:12',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&\-_]+$/',
            ],
            'share_location' => 'nullable|boolean',
            'share_location_hidden' => 'nullable|boolean',
            'is_public_profile' => 'nullable|boolean',
            'country_residence_auto' => 'nullable|string|max:255',
            'city_residence_auto' => 'nullable|string|max:255',
            'detected_latitude' => 'nullable|numeric',
            'detected_longitude' => 'nullable|numeric',
            'current_country_residence' => 'nullable|string|max:255',
            'current_city_residence' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'name.not_regex' => 'Le pseudo ne peut pas commencer ou finir par un point, tiret ou underscore.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'avatar doit être au format JPEG, JPG, PNG ou WebP.',
            'avatar.max' => 'L\'avatar ne doit pas dépasser 100KB.',
            'phone.regex' => 'Le numéro de téléphone doit contenir uniquement des chiffres, espaces, tirets et parenthèses.',
            'youtube_username.regex' => 'Le nom d\'utilisateur YouTube doit commencer par @ (ex: @monusername).',
            'instagram_username.regex' => 'Le nom d\'utilisateur Instagram peut contenir seulement des lettres, chiffres, points et underscores.',
            'tiktok_username.regex' => 'Le nom d\'utilisateur TikTok peut contenir seulement des lettres, chiffres, points et underscores (@ optionnel).',
            'linkedin_username.regex' => 'Le nom d\'utilisateur LinkedIn peut contenir des lettres, chiffres, tirets, points et underscores.',
            'twitter_username.regex' => 'Le nom d\'utilisateur Twitter peut contenir seulement des lettres, chiffres et underscores (@ optionnel).',
            'facebook_username.regex' => 'Le nom d\'utilisateur Facebook peut contenir des lettres, chiffres, points, underscores et tirets.',
            'telegram_username.regex' => 'Le nom d\'utilisateur Telegram peut contenir seulement des lettres, chiffres et underscores (@ optionnel).',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 12 caractères.',
            'new_password.regex' => 'Le nouveau mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validation de sécurité : vérifier que les champs cachés correspondent aux données actuelles
        if ($request->filled('current_country_residence') && 
            $request->input('current_country_residence') !== $user->country_residence) {
            return back()->withErrors(['security' => 'Données de formulaire invalides détectées.'])->withInput();
        }
        
        if ($request->filled('current_city_residence') && 
            $request->input('current_city_residence') !== $user->city_residence) {
            return back()->withErrors(['security' => 'Données de formulaire invalides détectées.'])->withInput();
        }


        // Gérer l'avatar avec gestion d'erreurs sécurisée
        $avatarPath = $user->avatar; // Conserver l'avatar actuel par défaut
        
        try {
            // Si l'utilisateur veut supprimer l'avatar
            if ($request->boolean('remove_avatar')) {
                if ($user->avatar) {
                    $this->deleteAvatarFile($user->avatar);
                }
                $avatarPath = null;
            }
            
            // Si un nouvel avatar est uploadé
            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar s'il existe
                if ($user->avatar) {
                    $this->deleteAvatarFile($user->avatar);
                }
                $avatarPath = $this->uploadAvatar($request->file('avatar'));
            }
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['avatar' => $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['avatar' => 'Erreur lors du traitement de l\'image.'])->withInput();
        }

        // Déterminer le pays et la ville selon le mode disponible
        $country = null;
        $city = null;
        
        if ($useAutoMode && $request->filled('country_residence_auto')) {
            // Mode automatique avec nouvelles données
            $country = $request->country_residence_auto;
            $city = $request->city_residence_auto;
        } elseif ($request->filled('country_residence')) {
            // Mode manuel avec nouvelles données
            $country = $request->country_residence;
            $city = $request->city_residence;
        } else {
            // Aucune nouvelle donnée fournie - utiliser les valeurs cachées de fallback
            // Ceci préserve les données même si les sections sont masquées
            $country = $request->input('current_country_residence', $user->country_residence);
            $city = $request->input('current_city_residence', $user->city_residence);
        }
        
        // Gérer la logique de la checkbox share_location (peut être désactivée)
        $shareLocation = false;
        if ($request->has('share_location') && $request->boolean('share_location')) {
            $shareLocation = true;
        } elseif ($request->has('share_location_hidden') && $request->boolean('share_location_hidden')) {
            $shareLocation = true;
        }
        
        // Mettre à jour les informations de l'utilisateur
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatarPath,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'country_residence' => $country,
            'destination_country' => $request->destination_country,
            'city_residence' => $city,
            'bio' => $request->bio,
            'youtube_username' => $request->youtube_username,
            'instagram_username' => $request->instagram_username,
            'tiktok_username' => $request->tiktok_username,
            'linkedin_username' => $request->linkedin_username,
            'twitter_username' => $request->twitter_username,
            'facebook_username' => $request->facebook_username,
            'telegram_username' => $request->telegram_username,
            'is_visible_on_map' => $shareLocation,
            'is_public_profile' => $request->boolean('is_public_profile', false),
        ];

        // Récupérer les coordonnées seulement si des nouvelles données sont fournies
        if ($useAutoMode && $request->filled('detected_latitude') && $request->filled('detected_longitude')) {
            // Mode automatique : utiliser les coordonnées détectées directement
            $updateData['latitude'] = $request->detected_latitude;
            $updateData['longitude'] = $request->detected_longitude;
            $updateData['city_detected'] = $city;
        } elseif ($request->filled('city_residence') && $request->filled('country_residence')) {
            // Mode manuel : lookup des coordonnées depuis le JSON
            try {
                $this->updateLocationCoordinates($updateData, $request->country_residence, $request->city_residence);
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer la mise à jour du profil
                \Log::warning('Failed to update location coordinates', [
                    'country' => $request->country_residence,
                    'city' => $request->city_residence,
                    'error' => $e->getMessage()
                ]);
            }
        }
        // Si aucune nouvelle donnée de localisation n'est fournie, garder les coordonnées actuelles

        // Ajouter le nouveau mot de passe s'il est fourni
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        $user->update($updateData);

        return back()->with('success', 'Votre profil a été mis à jour avec succès !');
    }

    /**
     * Clear user location data
     */
    public function clearLocation(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Vider les données de localisation
            $user->update([
                'latitude' => null,
                'longitude' => null,
                'city_residence' => null,
                'city_detected' => null,
                'is_visible_on_map' => false,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Données de localisation supprimées avec succès.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to clear location data', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression des données de localisation.'
            ], 500);
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
    
    /**
     * Securely delete avatar file with path validation
     */
    private function deleteAvatarFile(string $filename): void
    {
        // Validate filename to prevent path traversal
        if (empty($filename) || strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            throw new \InvalidArgumentException('Nom de fichier invalide.');
        }
        
        // Construct safe file path
        $avatarPath = public_path('storage/avatars/' . basename($filename));
        
        // Additional security: verify file is within avatars directory
        $realPath = realpath($avatarPath);
        $avatarsDir = realpath(public_path('storage/avatars'));
        
        if ($realPath && $avatarsDir && strpos($realPath, $avatarsDir) === 0 && file_exists($realPath)) {
            unlink($realPath);
        }
    }
    
    /**
     * Update location coordinates based on city and country selection
     */
    private function updateLocationCoordinates(array &$updateData, string $country, string $city): void
    {
        // Load local cities data from JSON file
        $jsonPath = database_path('data/cities_data.json');
        
        if (!file_exists($jsonPath)) {
            throw new \Exception('Cities data file not found');
        }
        
        $jsonContent = file_get_contents($jsonPath);
        $citiesData = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode cities JSON: ' . json_last_error_msg());
        }
        
        // Check if country exists in our data
        if (!isset($citiesData[$country])) {
            throw new \Exception("Country '{$country}' not found in cities data");
        }
        
        // Find the city
        $cityFound = false;
        foreach ($citiesData[$country] as $cityData) {
            if ($cityData['name'] === $city) {
                // Add coordinates to update data with privacy protection
                $updateData['latitude'] = $this->addPrivacyOffset($cityData['lat']);
                $updateData['longitude'] = $this->addPrivacyOffset($cityData['lng']);
                $updateData['city_detected'] = $city;
                $cityFound = true;
                break;
            }
        }
        
        if (!$cityFound) {
            throw new \Exception("City '{$city}' not found in country '{$country}'");
        }
    }
    
    /**
     * Add privacy offset to coordinates (similar to User model's randomizeCoordinates)
     */
    private function addPrivacyOffset(float $coordinate): float
    {
        // Add small random offset for privacy (approximately 10km radius)
        $offset = (random_int(-500, 500) / 10000); // Cryptographically secure random offset between -0.05 and 0.05 degrees
        return round($coordinate + $offset, 6);
    }
}