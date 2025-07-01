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
        ];

        // Ajouter le nouveau mot de passe s'il est fourni
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        $user->update($updateData);

        return back()->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}
