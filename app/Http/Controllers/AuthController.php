<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Services\EmailBlacklistService;
use App\Services\TurnstileService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected TurnstileService $turnstileService;
    protected EmailBlacklistService $blacklistService;

    public function __construct(TurnstileService $turnstileService, EmailBlacklistService $blacklistService)
    {
        $this->turnstileService = $turnstileService;
        $this->blacklistService = $blacklistService;
    }

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
        // Verify Turnstile protection
        $turnstileCheck = $this->verifyTurnstileForRequest($request, 'register');
        if ($turnstileCheck !== null) {
            return $turnstileCheck;
        }

        // Check email blacklist
        if ($this->blacklistService->isBlacklisted($request->input('email'))) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Cette adresse email n\'est pas autorisée. Veuillez utiliser une adresse email valide.']],
            ], 422);
        }

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
            'country_residence' => 'nullable|string|in:' . \App\Models\Country::pluck('name_fr')->join(','),
            'interest_country' => 'nullable|string|in:' . \App\Models\Country::pluck('name_fr')->join(','),
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{12,}$/',
            ],
            'terms' => 'required|accepted',
        ], [
            'name.regex' => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'name.not_regex' => 'Le pseudo ne peut pas commencer ou finir par un point, tiret ou underscore.',
            'country_residence.in' => 'Le pays de résidence sélectionné n\'est pas valide.',
            'interest_country.in' => 'Le pays d\'intérêt sélectionné n\'est pas valide.',
            'password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
            'interest_country.in' => 'Veuillez sélectionner un pays d\'intérêt valide.',
        ]);

        if ($validator->fails()) {
            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        // Validate country exists if provided (additional server-side check)
        if ($request->interest_country && ! Country::where('name_fr', $request->interest_country)->exists()) {
            return back()->withErrors(['interest_country' => 'Le pays d\'intérêt sélectionné n\'existe pas.'])->withInput();
        }

        // Créer le compte utilisateur (étape 1)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'interest_country' => $request->interest_country,
            'password' => Hash::make($request->password),
        ]);

        // Connecter l'utilisateur immédiatement
        Auth::login($user);

        // Envoyer l'email de vérification (sauf si désactivé en configuration)
        if (! config('services.email_verification.skip', false)) {
            try {
                $user->sendEmailVerificationNotification();
                \Log::info('Email verification sent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            // En développement, marquer automatiquement l'email comme vérifié
            $user->markEmailAsVerified();
            \Log::info('Email verification skipped - marked as verified automatically', [
                'user_id' => $user->id,
                'email' => $user->email,
                'environment' => app()->environment(),
            ]);
        }

        // Envoyer aussi l'email de bienvenue (en arrière-plan, ne pas bloquer si échec)
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            \Log::info('Welcome email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        // Retourner une réponse JSON redirigeant vers la page de vérification
        return response()->json([
            'success' => true,
            'message' => 'Compte créé avec succès ! Vérifiez votre email pour activer votre compte.',
            'redirect' => route('verification.notice'),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'interest_country' => $user->interest_country,
            ],
        ]);
    }

    public function enrichProfile(Request $request)
    {
        // Validation pour l'étape 2 : enrichissement du profil
        $validator = Validator::make($request->all(), [
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:500|mimetypes:image/jpeg,image/png,image/webp',
            'country_residence' => 'nullable|string|max:255',
            'city_residence' => 'nullable|string|max:255',
            'share_location' => 'nullable|boolean',
            'is_public_profile' => 'nullable|boolean',
            'initial_latitude' => 'nullable|numeric|between:-90,90',
            'initial_longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'avatar doit être au format JPEG, JPG, PNG ou WebP.',
            'avatar.max' => 'L\'avatar ne doit pas dépasser 500KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
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
                    'errors' => ['avatar' => [$e->getMessage()]],
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors' => ['avatar' => ['Erreur lors du traitement de l\'image.']],
                ], 422);
            }
        }

        // Déterminer si on utilise le mode automatique ou manuel
        $useAutoMode = $request->filled('country_residence_auto') && $request->filled('city_residence_auto');

        $country = $useAutoMode ? $request->country_residence_auto : $request->country_residence;
        $city = $useAutoMode ? $request->city_residence_auto : $request->city_residence;

        // Mettre à jour les informations du profil
        $updateData = [
            'avatar' => $avatarPath,
            'country_residence' => $country,
            'city_residence' => $city,
            'is_visible_on_map' => $request->boolean('share_location', false),
            'is_public_profile' => $request->boolean('is_public_profile', false),
        ];

        // Récupérer les coordonnées
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
                \Log::warning('Failed to update location coordinates during profile enrichment', [
                    'country' => $request->country_residence,
                    'city' => $request->city_residence,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $user->update($updateData);

        // Gérer la géolocalisation si demandée
        if ($request->filled('initial_latitude') && $request->filled('initial_longitude') && $user->is_visible_on_map) {
            try {
                $latitude = $request->input('initial_latitude');
                $longitude = $request->input('initial_longitude');

                $city = $this->getCityFromCoordinates($latitude, $longitude);
                $user->updateLocation($latitude, $longitude, $city);

                \Log::info('User location updated during profile enrichment', [
                    'user_id' => $user->id,
                    'city' => $city,
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to set location during profile enrichment', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil enrichi avec succès !',
            'redirect_url' => '/',
        ]);
    }

    public function login(Request $request)
    {
        // Verify Turnstile protection
        $turnstileCheck = $this->verifyTurnstileForRequest($request, 'login');
        if ($turnstileCheck !== null) {
            return $turnstileCheck;
        }

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
     * Verify Turnstile protection for a request.
     */
    private function verifyTurnstileForRequest(Request $request, string $action): ?Response
    {
        if (! $this->shouldVerifyTurnstile($request)) {
            return null;
        }

        if (! $this->turnstileService->verify($request->input('cf-turnstile-response'), $action)) {
            $errorMessage = 'Vérification de sécurité échouée. Veuillez réessayer.';

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['turnstile' => [$errorMessage]],
                ], 422);
            }

            return back()->withErrors(['turnstile' => $errorMessage])->withInput();
        }

        return null;
    }

    /**
     * Determine if Turnstile verification should be performed.
     */
    private function shouldVerifyTurnstile(Request $request): bool
    {
        return $this->turnstileService->isConfigured() && ! app()->environment('local');
    }

    /**
     * Anonymize IP address for GDPR compliance.
     */
    private function anonymizeIp(string $ip): string
    {
        // For IPv4: zero out the last octet
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';

            return implode('.', $parts);
        }

        // For IPv6: zero out the last 64 bits
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            for ($i = 4; $i < count($parts); $i++) {
                $parts[$i] = '0';
            }

            return implode(':', $parts);
        }

        return 'unknown';
    }

    /**
     * Check username availability for real-time validation.
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
                    'message' => 'Le pseudo doit contenir au moins 3 caractères.',
                ]);
            }

            // Check if username matches regex requirements
            if (! preg_match('/^[a-zA-Z0-9_.-]+$/', $cleanedUsername)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
                ]);
            }

            // Check if starts or ends with special characters
            if (preg_match('/^[._-]/', $cleanedUsername) || preg_match('/[._-]$/', $cleanedUsername)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Le pseudo ne peut pas commencer ou finir par un point, tiret ou underscore.',
                ]);
            }

            // Check if username exists (case-insensitive)
            $exists = User::whereRaw('LOWER(name) = ?', [strtolower($cleanedUsername)])->exists();

            // Also check if the generated slug would conflict
            $slug = User::generateSlug($cleanedUsername);
            $slugExists = User::whereRaw('LOWER(name_slug) = ?', [strtolower($slug)])->exists();

            if ($exists || $slugExists) {
                return response()->json([
                    'available' => false,
                    'message' => 'Ce pseudo est déjà pris.',
                ]);
            }

            return response()->json([
                'available' => true,
                'message' => 'Ce pseudo est disponible.',
            ]);

        } catch (\Exception $e) {
            \Log::error('Username check error', [
                'username' => $username,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'available' => false,
                'message' => 'Erreur lors de la vérification.',
            ]);
        }
    }

    /**
     * Get city name from coordinates using reverse geocoding with caching and retry logic.
     */
    private function getCityFromCoordinates(float $latitude, float $longitude): string
    {
        try {
            // Create cache key based on rounded coordinates (for privacy and performance)
            $roundedLat = round($latitude, 2);
            $roundedLng = round($longitude, 2);
            $cacheKey = "geocoding_{$roundedLat}_{$roundedLng}";

            // Check cache first (1 hour cache for successful responses)
            if (\Cache::has($cacheKey)) {
                return \Cache::get($cacheKey);
            }

            // Check if we have a recent failure cached (5 minute cache for failures)
            $failureCacheKey = "geocoding_failure_{$roundedLat}_{$roundedLng}";
            if (\Cache::has($failureCacheKey)) {
                return 'Ville inconnue';
            }

            $city = $this->attemptGeocoding($latitude, $longitude);

            if ($city && $city !== 'Ville inconnue') {
                // Cache successful result for 1 hour
                \Cache::put($cacheKey, $city, 3600);

                return $city;
            } else {
                // Cache failure for 5 minutes to avoid repeated requests
                \Cache::put($failureCacheKey, true, 300);

                return 'Ville inconnue';
            }

        } catch (\Exception $e) {
            \Log::warning('Failed to get city from coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'error' => $e->getMessage(),
            ]);

            // Cache failure for 5 minutes
            $failureCacheKey = "geocoding_failure_{$roundedLat}_{$roundedLng}";
            \Cache::put($failureCacheKey, true, 300);

            return 'Ville inconnue';
        }
    }

    /**
     * Attempt geocoding with retry logic and multiple fallback APIs.
     */
    private function attemptGeocoding(float $latitude, float $longitude): string
    {
        $apis = [
            // Primary: Nominatim OpenStreetMap
            [
                'url' => "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10&addressdetails=1",
                'headers' => [
                    'User-Agent: Sekaijin/1.0 (contact@sekaijin.com)',
                    'Accept: application/json',
                ],
                'parser' => 'parseNominatimResponse',
            ],
            // Fallback: OpenCage (if you have an API key)
            // [
            //     'url' => "https://api.opencagedata.com/geocode/v1/json?q={$latitude}+{$longitude}&key=YOUR_API_KEY",
            //     'headers' => ['Accept: application/json'],
            //     'parser' => 'parseOpenCageResponse'
            // ]
        ];

        foreach ($apis as $api) {
            $maxRetries = 2;
            $retryDelay = 1; // seconds

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 8,
                            'method' => 'GET',
                            'header' => implode("\r\n", $api['headers']),
                            'ignore_errors' => true,
                        ],
                    ]);

                    $response = file_get_contents($api['url'], false, $context);

                    if ($response === false) {
                        throw new \Exception('HTTP request failed');
                    }

                    // Check HTTP status code
                    $httpStatus = $this->parseHttpStatus($http_response_header ?? []);
                    if ($httpStatus >= 400) {
                        throw new \Exception("HTTP {$httpStatus} error");
                    }

                    $city = $this->{$api['parser']}($response);

                    if ($city && $city !== 'Ville inconnue') {
                        return $city;
                    }

                    break; // Don't retry if parsing was successful but no city found

                } catch (\Exception $e) {
                    \Log::debug("Geocoding attempt {$attempt} failed", [
                        'api' => $api['url'],
                        'error' => $e->getMessage(),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);

                    if ($attempt < $maxRetries) {
                        sleep($retryDelay);
                        $retryDelay *= 2; // Exponential backoff
                    }
                }
            }
        }

        return 'Ville inconnue';
    }

    /**
     * Parse HTTP status from response headers.
     */
    private function parseHttpStatus(array $headers): int
    {
        foreach ($headers as $header) {
            if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                return (int) $matches[1];
            }
        }

        return 200; // Default to OK if not found
    }

    /**
     * Parse Nominatim API response.
     */
    private function parseNominatimResponse(string $response): string
    {
        $data = json_decode($response, true);

        if (! $data || ! isset($data['address'])) {
            return 'Ville inconnue';
        }

        // Extract city name from various possible fields
        $address = $data['address'];

        return $address['city'] ??
               $address['town'] ??
               $address['village'] ??
               $address['municipality'] ??
               $address['county'] ??
               $address['state'] ??
               'Ville inconnue';
    }

    /**
     * Upload and optimize avatar image.
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
        if (! file_exists($avatarPath)) {
            mkdir($avatarPath, 0755, true);
        }

        // Move the file
        $file->move($avatarPath, $filename);

        return $filename;
    }

    /**
     * Validate image file content and headers.
     */
    private function validateImageFile($file): void
    {
        // Check file size (double-check on server side)
        if ($file->getSize() > 500 * 1024) {
            throw new \InvalidArgumentException('Le fichier est trop volumineux. Maximum 500KB autorisé.');
        }

        // Validate MIME type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Type de fichier non autorisé. Seuls JPEG, PNG et WebP sont acceptés.');
        }

        // Validate file headers (magic bytes)
        $fileContent = file_get_contents($file->getPathname());
        $validHeaders = [
            'jpeg' => ["\xFF\xD8\xFF"],
            'png' => ["\x89\x50\x4E\x47\x0D\x0A\x1A\x0A"],
            'webp' => ['RIFF', 'WEBP'],
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

        if (! $isValidHeader) {
            throw new \InvalidArgumentException('Fichier image corrompu ou invalide.');
        }
    }

    /**
     * Update location coordinates based on city and country selection.
     */
    private function updateLocationCoordinates(array &$updateData, string $country, string $city): void
    {
        // Load local cities data from JSON file
        $jsonPath = database_path('data/cities_data.json');

        if (! file_exists($jsonPath)) {
            throw new \Exception('Cities data file not found');
        }

        $jsonContent = file_get_contents($jsonPath);
        $citiesData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode cities JSON: ' . json_last_error_msg());
        }

        // Check if country exists in our data
        if (! isset($citiesData[$country])) {
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

        if (! $cityFound) {
            throw new \Exception("City '{$city}' not found in country '{$country}'");
        }
    }

    /**
     * Add privacy offset to coordinates (similar to User model's randomizeCoordinates).
     */
    private function addPrivacyOffset(float $coordinate): float
    {
        // Add small random offset for privacy (approximately 10km radius)
        $offset = (rand(-500, 500) / 10000); // Random offset between -0.05 and 0.05 degrees

        return round($coordinate + $offset, 6);
    }

    /**
     * Show the email verification notice.
     */
    public function showVerifyEmail(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended('/')
            : view('auth.verify-email');
    }

    /**
     * Verify the user's email address.
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        event(new Verified($request->user()));

        return redirect('/')->with('status', 'verified');
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('message', 'Votre email est déjà vérifié.');
        }

        try {
            $request->user()->sendEmailVerificationNotification();

            \Log::info('Email verification sent successfully', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return back()->with('message', 'Email de vérification renvoyé ! Vérifiez votre boîte de réception et vos spams.');

        } catch (\Exception $e) {
            \Log::error('Failed to send email verification', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['email' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer dans quelques minutes ou contacter le support.']);
        }
    }

    /**
     * Show the form for requesting a password reset link.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Verify Turnstile protection
        $turnstileCheck = $this->verifyTurnstileForRequest($request, 'forgot-password');
        if ($turnstileCheck !== null) {
            return $turnstileCheck;
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = \Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Password::RESET_LINK_SENT
                    ? redirect()->route('home')->with(['success' => 'Nous vous avons envoyé par email un lien de réinitialisation de mot de passe !'])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        // Verify Turnstile protection
        $turnstileCheck = $this->verifyTurnstileForRequest($request, 'reset-password');
        if ($turnstileCheck !== null) {
            return $turnstileCheck;
        }

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = \Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(\Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status === \Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
