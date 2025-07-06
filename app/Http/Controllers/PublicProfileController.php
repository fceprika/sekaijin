<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        // Optimized: Use indexed name_slug for fast lookup
        $slug = strtolower(trim($name));
        $user = User::where('name_slug', $slug)->first();
        
        if (!$user) {
            abort(404, 'Membre introuvable');
        }
        
        // Redirection canonique si l'URL n'est pas déjà en slug format
        if ($name !== $user->name_slug) {
            return redirect()->route(User::ROUTE_PUBLIC_PROFILE, $user->name_slug, 301);
        }
        
        return view('profile.public', compact('user'));
    }
}