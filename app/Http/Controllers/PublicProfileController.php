<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('member.invitation');
        }
        
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
        
        // Get user's created events (upcoming and past)
        $upcomingEvents = Event::where('organizer_id', $user->id)
            ->published()
            ->upcoming()
            ->with(['country'])
            ->orderBy('start_date', 'asc')
            ->get();
            
        $pastEvents = Event::where('organizer_id', $user->id)
            ->published()
            ->where('start_date', '<', now())
            ->with(['country'])
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();
        
        return view('profile.public', compact('user', 'upcomingEvents', 'pastEvents'));
    }
}