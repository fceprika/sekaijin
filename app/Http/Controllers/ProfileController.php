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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'country_residence' => 'required|string|max:255',
            'city_residence' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'youtube_username' => 'nullable|string|max:255|regex:/^@[a-zA-Z0-9_.-]+$/',
            'instagram_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_.]+$/',
            'tiktok_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_.]+$/',
            'linkedin_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\-]+$/',
            'twitter_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_]+$/',
            'facebook_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9.]+$/',
            'telegram_username' => 'nullable|string|max:255|regex:/^@?[a-zA-Z0-9_]+$/',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed|different:current_password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mettre à jour les informations de l'utilisateur
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'country_residence' => $request->country_residence,
            'city_residence' => $request->city_residence,
            'bio' => $request->bio,
            'youtube_username' => $request->youtube_username,
            'instagram_username' => $request->instagram_username,
            'tiktok_username' => $request->tiktok_username,
            'linkedin_username' => $request->linkedin_username,
            'twitter_username' => $request->twitter_username,
            'facebook_username' => $request->facebook_username,
            'telegram_username' => $request->telegram_username,
        ];

        // Ajouter le nouveau mot de passe s'il est fourni
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        $user->update($updateData);

        return back()->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}