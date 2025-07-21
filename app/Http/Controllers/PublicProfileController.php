<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Services\SeoService;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        // Recherche optimisée avec le champ indexé name_slug
        $slug = strtolower(trim($name));
        $user = User::where('name_slug', $slug)->first();

        if (! $user) {
            abort(404, 'Membre introuvable');
        }

        // Vérifier si le profil est public ou si l'utilisateur est connecté
        if (! $user->is_public_profile && ! auth()->check()) {
            return redirect()->route('member.invitation');
        }

        // Vérifier que l'utilisateur a vérifié son email (profils publics uniquement pour emails vérifiés)
        if ($user->is_public_profile && ! $user->hasVerifiedEmail()) {
            abort(404, 'Profil non disponible');
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

        // Generate SEO data for profile page
        $seoService = new SeoService;
        $seoData = $seoService->generateSeoData('profile', $user);
        $structuredData = $seoService->generateStructuredData('profile', $user);

        return view('profile.public', compact('user', 'upcomingEvents', 'pastEvents', 'seoData', 'structuredData'));
    }
}
