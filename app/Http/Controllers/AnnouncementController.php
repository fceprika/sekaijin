<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['globalIndex', 'show']);
    }

    public function globalIndex(Request $request)
    {
        $query = Announcement::active()->with('user');

        // Filtres
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('country')) {
            $query->inCountry($request->country);
        }

        if ($request->filled('city')) {
            $query->inCity($request->city);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        $announcements = $query->paginate(12);
        $countries = Country::orderBy('name_fr')->get();

        // Obtenir les villes pour le pays sélectionné
        $cities = collect([]);
        if ($request->filled('country')) {
            $cities = Announcement::active()
                ->inCountry($request->country)
                ->distinct()
                ->pluck('city')
                ->sort()
                ->values();
        }

        return view('announcements.index', compact('announcements', 'countries', 'cities'));
    }

    public function create()
    {
        $countries = Country::orderBy('name_fr')->get();
        return view('announcements.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:vente,location,colocation,service',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'expiration_date' => 'nullable|date|after:today'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Gestion des images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('announcements', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $announcement = Announcement::create($validated);

        // Déterminer la route de redirection selon le contexte
        $country = Country::where('name_fr', $announcement->country)->first();
        if ($country) {
            return redirect()->route('country.announcement.show', [$country->slug, $announcement])
                ->with('success', 'Votre annonce a été créée et est en attente de validation.');
        } else {
            return redirect()->route('announcements.show', $announcement)
                ->with('success', 'Votre annonce a été créée et est en attente de validation.');
        }
    }

    public function show(Announcement $announcement)
    {
        // Vérifier si l'annonce est visible
        if (!$announcement->isActive() && 
            (!Auth::check() || !$announcement->canBeEditedBy(Auth::user()))) {
            abort(404);
        }

        $announcement->incrementViews();
        $announcement->load('user');

        // Annonces similaires
        $similarAnnouncements = Announcement::active()
            ->where('id', '!=', $announcement->id)
            ->where('type', $announcement->type)
            ->where('country', $announcement->country)
            ->limit(4)
            ->get();

        return view('announcements.show', compact('announcement', 'similarAnnouncements'));
    }

    public function edit(Announcement $announcement)
    {
        if (!$announcement->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        $countries = Country::orderBy('name_fr')->get();
        return view('announcements.edit', compact('announcement', 'countries'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (!$announcement->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:vente,location,colocation,service',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'expiration_date' => 'nullable|date|after:today',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string'
        ]);

        // Gestion de la suppression d'images
        if ($request->has('remove_images')) {
            $currentImages = $announcement->images ?? [];
            foreach ($request->remove_images as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                    Storage::disk('public')->delete($imageToRemove);
                }
            }
            $validated['images'] = array_values($currentImages);
        }

        // Ajout de nouvelles images
        if ($request->hasFile('images')) {
            $currentImages = $validated['images'] ?? $announcement->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('announcements', 'public');
                $currentImages[] = $path;
            }
            $validated['images'] = $currentImages;
        }

        // Réinitialiser le statut si l'annonce était refusée
        if ($announcement->isRefused()) {
            $validated['status'] = 'pending';
        }

        $announcement->update($validated);

        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Votre annonce a été mise à jour.');
    }

    public function destroy(Announcement $announcement)
    {
        if (!$announcement->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        // Supprimer les images
        if ($announcement->images) {
            foreach ($announcement->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Votre annonce a été supprimée.');
    }

    public function myAnnouncements()
    {
        $announcements = Auth::user()->announcements()
            ->latest()
            ->paginate(10);

        return view('announcements.my-announcements', compact('announcements'));
    }
}
