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

        $validator = Validator::make($request->all(), [
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
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:100',
            'remove_avatar' => 'nullable|boolean',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'country_residence' => 'required|string|max:255',
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

        // Gérer l'avatar
        $avatarPath = $user->avatar; // Conserver l'avatar actuel par défaut
        
        // Si l'utilisateur veut supprimer l'avatar
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && file_exists(public_path('storage/avatars/' . $user->avatar))) {
                unlink(public_path('storage/avatars/' . $user->avatar));
            }
            $avatarPath = null;
        }
        
        // Si un nouvel avatar est uploadé
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && file_exists(public_path('storage/avatars/' . $user->avatar))) {
                unlink(public_path('storage/avatars/' . $user->avatar));
            }
            $avatarPath = $this->uploadAvatar($request->file('avatar'));
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
            'country_residence' => $request->country_residence,
            'destination_country' => $request->destination_country,
            'city_residence' => $request->city_residence,
            'bio' => $request->bio,
            'youtube_username' => $request->youtube_username,
            'instagram_username' => $request->instagram_username,
            'tiktok_username' => $request->tiktok_username,
            'linkedin_username' => $request->linkedin_username,
            'twitter_username' => $request->twitter_username,
            'facebook_username' => $request->facebook_username,
            'telegram_username' => $request->telegram_username,
            'is_visible_on_map' => $request->boolean('share_location', false),
        ];

        // Ajouter le nouveau mot de passe s'il est fourni
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        $user->update($updateData);

        return back()->with('success', 'Votre profil a été mis à jour avec succès !');
    }

    /**
     * Upload and optimize avatar image
     */
    private function uploadAvatar($file): string
    {
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Ensure avatars directory exists
        $avatarPath = public_path('storage/avatars');
        if (!file_exists($avatarPath)) {
            mkdir($avatarPath, 0755, true);
        }
        
        // Move the file
        $file->move($avatarPath, $filename);
        
        return $filename;
    }
}