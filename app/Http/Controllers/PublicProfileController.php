<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        // Recherche insensible à la casse
        $user = User::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        
        if (!$user) {
            abort(404, 'Membre introuvable');
        }
        
        // Redirection canonique vers l'URL en minuscules si nécessaire
        $canonicalName = strtolower($user->name);
        if ($name !== $canonicalName) {
            return redirect()->route('public.profile', $canonicalName, 301);
        }
        
        return view('profile.public', compact('user'));
    }
}