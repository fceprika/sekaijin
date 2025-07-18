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

        // Récupérer la liste des champs modifiés depuis le client
        $changedFields = [];
        if ($request->has('changed_fields')) {
            $changedFields = json_decode($request->input('changed_fields'), true) ?: [];
        }
        
        // Si aucun changement détecté côté client, vérifier côté serveur
        if (empty($changedFields)) {
            $changedFields = $this->detectServerSideChanges($request, $user);
        }
        
        // Si vraiment aucun changement, retourner avec un message
        if (empty($changedFields)) {
            return back()->with('info', 'Aucun changement détecté. Profil non modifié.');
        }

        // Log des champs modifiés pour debug
        \Log::info('Profile update - Changed fields', [
            'user_id' => $user->id,
            'changed_fields' => $changedFields,
            'client_detected' => $request->has('changed_fields')
        ]);

        // Déterminer si on utilise le mode automatique ou manuel AVANT la validation
        $useAutoMode = $request->filled('country_residence_auto') && $request->filled('city_residence_auto');
        
        // Préparer les données pour la validation (seulement les champs modifiés + champs requis)
        $validationData = $this->prepareValidationData($request, $changedFields, $useAutoMode);

        // Construire les règles de validation dynamiquement selon les champs modifiés
        $validationRules = $this->buildValidationRules($changedFields, $user);
        
        $validator = Validator::make($validationData, $validationRules, [
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

        // Construire les données de mise à jour en utilisant seulement les champs modifiés
        $updateData = $this->buildUpdateData($request, $changedFields, $user, $avatarPath, $useAutoMode);

        // Récupérer les coordonnées seulement si les champs de localisation ont été modifiés
        if (in_array('location_coordinates', $changedFields) || 
            in_array('country_residence', $changedFields) || 
            in_array('city_residence', $changedFields)) {
            
            if ($useAutoMode && $request->filled('detected_latitude') && $request->filled('detected_longitude')) {
                // Mode automatique : utiliser les coordonnées détectées directement
                $updateData['latitude'] = $request->detected_latitude;
                $updateData['longitude'] = $request->detected_longitude;
                $updateData['city_detected'] = $updateData['city_residence'] ?? $user->city_residence;
            } elseif ($request->filled('city_residence') && $request->filled('country_residence')) {
                // Mode manuel : lookup des coordonnées depuis le JSON
                try {
                    $this->updateLocationCoordinates($updateData, $request->country_residence, $request->city_residence);
                } catch (\Exception $e) {
                    // Messages d'erreur spécifiques selon le type d'erreur
                    $errorMessage = match($e->getMessage()) {
                        'Cities data file not found' => 'Service de géolocalisation temporairement indisponible.',
                        'Failed to decode cities JSON: Syntax error' => 'Erreur de configuration géographique.',
                        default => 'Erreur de localisation. Veuillez réessayer.'
                    };
                    
                    // Log détaillé pour debugging
                    \Log::warning('Location coordinates update failed', [
                        'country' => $request->country_residence,
                        'city' => $request->city_residence,
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'user_message' => $errorMessage
                    ]);
                    
                    // Ajouter un message flash pour informer l'utilisateur
                    session()->flash('location_warning', $errorMessage);
                }
            }
        }

        // Effectuer la mise à jour seulement s'il y a des données à mettre à jour
        if (!empty($updateData)) {
            $user->update($updateData);
            
            // Log de la mise à jour réussie
            \Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($updateData),
                'changed_fields_count' => count($changedFields)
            ]);
            
            return back()->with('success', 'Votre profil a été mis à jour avec succès !');
        } else {
            // Aucune donnée à mettre à jour
            return back()->with('info', 'Aucune modification détectée. Votre profil n\'a pas été changé.');
        }
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
        
        // Additional server-side image validation using getimagesize()
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \InvalidArgumentException('Fichier image non valide ou corrompu.');
        }
        
        // Validate image dimensions (optional but recommended)
        list($width, $height) = $imageInfo;
        if ($width < 32 || $height < 32) {
            throw new \InvalidArgumentException('L\'image doit faire au minimum 32x32 pixels.');
        }
        
        if ($width > 2048 || $height > 2048) {
            throw new \InvalidArgumentException('L\'image ne doit pas dépasser 2048x2048 pixels.');
        }
        
        // Verify MIME type consistency between getimagesize and file extension
        $allowedImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
        if (!in_array($imageInfo[2], $allowedImageTypes)) {
            throw new \InvalidArgumentException('Format d\'image non supporté.');
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

    /**
     * Détecter les changements côté serveur en comparant avec les données actuelles
     */
    private function detectServerSideChanges(Request $request, $user): array
    {
        // Si le client a déjà détecté des changements, valider et utiliser cette liste
        if ($request->has('changed_fields')) {
            $clientChanges = json_decode($request->input('changed_fields'), true) ?: [];
            return $this->validateClientChanges($clientChanges, $request, $user);
        }
        
        $changedFields = [];
        
        // Mapping des champs à vérifier
        $fieldsToCheck = [
            'name' => $user->name,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'birth_date' => $user->birth_date,
            'phone' => $user->phone,
            'bio' => $user->bio,
            'youtube_username' => $user->youtube_username,
            'instagram_username' => $user->instagram_username,
            'tiktok_username' => $user->tiktok_username,
            'linkedin_username' => $user->linkedin_username,
            'twitter_username' => $user->twitter_username,
            'facebook_username' => $user->facebook_username,
            'telegram_username' => $user->telegram_username,
            'is_public_profile' => $user->is_public_profile,
            'interest_country' => $user->interest_country,
        ];

        // Vérifier les champs de localisation selon le mode
        $useAutoMode = $request->filled('country_residence_auto') && $request->filled('city_residence_auto');
        if ($useAutoMode) {
            $fieldsToCheck['country_residence'] = $user->country_residence;
            $fieldsToCheck['city_residence'] = $user->city_residence;
        } else {
            $fieldsToCheck['country_residence'] = $user->country_residence;
            $fieldsToCheck['city_residence'] = $user->city_residence;
        }

        // Comparer chaque champ
        foreach ($fieldsToCheck as $field => $currentValue) {
            $newValue = $request->input($field);
            
            // Gestion spéciale pour les booléens
            if (in_array($field, ['is_public_profile'])) {
                $newValue = $request->boolean($field, false);
                $currentValue = (bool) $currentValue;
            }
            
            // Normaliser les valeurs pour la comparaison
            $normalizedNew = $newValue === null ? '' : (string) $newValue;
            $normalizedCurrent = $currentValue === null ? '' : (string) $currentValue;
            
            if ($normalizedNew !== $normalizedCurrent) {
                $changedFields[] = $field;
            }
        }

        // Vérifier les changements spéciaux
        if ($request->hasFile('avatar') || $request->boolean('remove_avatar')) {
            $changedFields[] = 'avatar';
        }

        if ($request->filled('new_password')) {
            $changedFields[] = 'password';
        }

        // Vérifier les changements de localisation (coordonnées)
        if ($request->filled('detected_latitude') || $request->filled('detected_longitude')) {
            $changedFields[] = 'location_coordinates';
        }

        return array_unique($changedFields);
    }

    /**
     * Préparer les données de validation en incluant seulement les champs nécessaires
     */
    private function prepareValidationData(Request $request, array $changedFields, bool $useAutoMode): array
    {
        $validationData = [];
        
        // Toujours inclure le token CSRF
        $validationData['_token'] = $request->input('_token');
        
        // Toujours inclure les champs requis pour éviter les erreurs de validation
        $alwaysInclude = ['name', 'email'];
        
        // Le pays d'intérêt est maintenant indépendant du pays de résidence
        
        // Inclure les champs modifiés + champs toujours requis
        $fieldsToInclude = array_unique(array_merge($changedFields, $alwaysInclude));
        
        foreach ($fieldsToInclude as $field) {
            if ($request->has($field)) {
                $validationData[$field] = $request->input($field);
            }
        }

        // Inclure les champs de sécurité cachés s'ils existent
        if ($request->has('current_country_residence')) {
            $validationData['current_country_residence'] = $request->input('current_country_residence');
        }
        if ($request->has('current_city_residence')) {
            $validationData['current_city_residence'] = $request->input('current_city_residence');
        }

        // Gestion spéciale des champs de localisation
        if ($useAutoMode) {
            $validationData['country_residence_auto'] = $request->input('country_residence_auto');
            $validationData['city_residence_auto'] = $request->input('city_residence_auto');
            $validationData['detected_latitude'] = $request->input('detected_latitude');
            $validationData['detected_longitude'] = $request->input('detected_longitude');
            // Utiliser les données auto pour la validation de country_residence
            $validationData['country_residence'] = $request->input('country_residence_auto');
            $validationData['city_residence'] = $request->input('city_residence_auto');
        } else {
            // En mode manuel, s'assurer que les champs auto sont vides
            $validationData['country_residence_auto'] = null;
            $validationData['city_residence_auto'] = null;
            if (in_array('country_residence', $changedFields) || in_array('city_residence', $changedFields)) {
                $validationData['country_residence'] = $request->input('country_residence');
                $validationData['city_residence'] = $request->input('city_residence');
            }
        }

        // Inclure les champs spéciaux si nécessaires
        if (in_array('avatar', $changedFields)) {
            $validationData['avatar'] = $request->file('avatar');
            $validationData['remove_avatar'] = $request->boolean('remove_avatar');
        }

        if (in_array('password', $changedFields)) {
            $validationData['current_password'] = $request->input('current_password');
            $validationData['new_password'] = $request->input('new_password');
            $validationData['new_password_confirmation'] = $request->input('new_password_confirmation');
        }

        // Inclure les champs de partage de localisation si modifiés
        if (in_array('share_location', $changedFields) || in_array('share_location_hidden', $changedFields)) {
            $validationData['share_location'] = $request->input('share_location');
            $validationData['share_location_hidden'] = $request->input('share_location_hidden');
        }

        return $validationData;
    }

    /**
     * Construire les données de mise à jour en utilisant seulement les champs modifiés
     */
    private function buildUpdateData(Request $request, array $changedFields, $user, $avatarPath, bool $useAutoMode): array
    {
        $updateData = [];

        // Debug log pour voir les champs modifiés
        \Log::debug('Building update data', [
            'changed_fields' => $changedFields,
            'use_auto_mode' => $useAutoMode
        ]);

        // Mapping des champs simples
        $simpleFields = [
            'name', 'email', 'first_name', 'last_name', 'birth_date', 'phone', 'bio',
            'youtube_username', 'instagram_username', 'tiktok_username', 'linkedin_username',
            'twitter_username', 'facebook_username', 'telegram_username', 'interest_country'
        ];

        foreach ($simpleFields as $field) {
            if (in_array($field, $changedFields)) {
                $updateData[$field] = $request->input($field);
                \Log::debug("Added field to update: {$field}", ['value' => $request->input($field)]);
            }
        }

        // Champs spéciaux
        if (in_array('avatar', $changedFields)) {
            $updateData['avatar'] = $avatarPath;
        }

        if (in_array('is_public_profile', $changedFields)) {
            $updateData['is_public_profile'] = $request->boolean('is_public_profile', false);
        }

        // Gestion de la localisation
        if (in_array('country_residence', $changedFields) || in_array('city_residence', $changedFields) || in_array('location_coordinates', $changedFields)) {
            if ($useAutoMode && $request->filled('country_residence_auto')) {
                $updateData['country_residence'] = $request->country_residence_auto;
                $updateData['city_residence'] = $request->city_residence_auto;
            } elseif ($request->filled('country_residence')) {
                $updateData['country_residence'] = $request->country_residence;
                $updateData['city_residence'] = $request->city_residence;
            } else {
                // Garder les valeurs actuelles
                $updateData['country_residence'] = $request->input('current_country_residence', $user->country_residence);
                $updateData['city_residence'] = $request->input('current_city_residence', $user->city_residence);
            }
        }

        // Partage de localisation
        if (in_array('share_location', $changedFields) || in_array('share_location_hidden', $changedFields)) {
            $shareLocation = false;
            if ($request->has('share_location') && $request->boolean('share_location')) {
                $shareLocation = true;
            } elseif ($request->has('share_location_hidden') && $request->boolean('share_location_hidden')) {
                $shareLocation = true;
            }
            $updateData['is_visible_on_map'] = $shareLocation;
        }

        // Mot de passe
        if (in_array('password', $changedFields) && $request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        return $updateData;
    }

    /**
     * Construire les règles de validation dynamiquement selon les champs modifiés
     */
    private function buildValidationRules(array $changedFields, $user): array
    {
        $rules = [];

        // Règles de base (toujours appliquées)
        $baseRules = [
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
        ];

        // Ajouter les règles de base
        foreach ($baseRules as $field => $rule) {
            $rules[$field] = $rule;
        }

        // Règles conditionnelles selon les champs modifiés
        $conditionalRules = [
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:100|mimetypes:image/jpeg,image/png,image/webp',
            'remove_avatar' => 'nullable|boolean',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'country_residence' => 'nullable|string|max:255',
            'interest_country' => [
                'nullable', 
                'string', 
                'max:255'
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
        ];

        // Ajouter seulement les règles des champs qui sont présents dans les données
        foreach ($conditionalRules as $field => $rule) {
            // Inclure la règle si le champ est modifié OU si c'est un champ spécial toujours nécessaire
            if (in_array($field, $changedFields) || 
                in_array($field, ['current_country_residence', 'current_city_residence', 'country_residence_auto', 'city_residence_auto', 'detected_latitude', 'detected_longitude'])) {
                
                // Le pays d'intérêt est maintenant indépendant du pays de résidence
                
                $rules[$field] = $rule;
            }
        }

        return $rules;
    }

    /**
     * Valider les changements détectés côté client contre les données réelles
     */
    private function validateClientChanges(array $clientChanges, Request $request, $user): array
    {
        $validatedChanges = [];
        
        // Mapping des champs à vérifier pour validation
        $fieldsToValidate = [
            'name' => $user->name,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'birth_date' => $user->birth_date,
            'phone' => $user->phone,
            'bio' => $user->bio,
            'youtube_username' => $user->youtube_username,
            'instagram_username' => $user->instagram_username,
            'tiktok_username' => $user->tiktok_username,
            'linkedin_username' => $user->linkedin_username,
            'twitter_username' => $user->twitter_username,
            'facebook_username' => $user->facebook_username,
            'telegram_username' => $user->telegram_username,
            'is_public_profile' => $user->is_public_profile,
            'interest_country' => $user->interest_country,
            'country_residence' => $user->country_residence,
            'city_residence' => $user->city_residence,
        ];

        // Valider chaque changement déclaré par le client
        foreach ($clientChanges as $field) {
            if (array_key_exists($field, $fieldsToValidate)) {
                $newValue = $request->input($field);
                $currentValue = $fieldsToValidate[$field];
                
                // Gestion spéciale pour les booléens
                if (in_array($field, ['is_public_profile'])) {
                    $newValue = $request->boolean($field, false);
                    $currentValue = (bool) $currentValue;
                }
                
                // Normaliser pour comparaison
                $normalizedNew = $newValue === null ? '' : (string) $newValue;
                $normalizedCurrent = $currentValue === null ? '' : (string) $currentValue;
                
                if ($normalizedNew !== $normalizedCurrent) {
                    $validatedChanges[] = $field;
                }
            }
        }

        // Ajouter les changements spéciaux toujours détectés côté serveur
        if ($request->hasFile('avatar') || $request->boolean('remove_avatar')) {
            $validatedChanges[] = 'avatar';
        }

        if ($request->filled('new_password')) {
            $validatedChanges[] = 'password';
        }

        if ($request->filled('detected_latitude') || $request->filled('detected_longitude')) {
            $validatedChanges[] = 'location_coordinates';
        }

        return array_unique($validatedChanges);
    }
}