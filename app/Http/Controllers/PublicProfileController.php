<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        // Recherche optimisée avec le champ indexé name_slug
        $slug = strtolower(trim($name));
        $user = User::where('name_slug', $slug)->first();
        
        if (!$user) {
            abort(404, 'Membre introuvable');
        }
        
        // Redirection canonique vers l'URL en minuscules si nécessaire
        if ($name !== $user->name_slug) {
            return redirect()->route(User::ROUTE_PUBLIC_PROFILE, $user->name_slug, 301);
        }
        
        return view('profile.public', compact('user'));
    }
}