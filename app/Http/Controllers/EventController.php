<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Country;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $countrySlug = $request->query('country');
        $country = null;
        
        if ($countrySlug) {
            $country = Country::where('slug', $countrySlug)->first();
        }
        
        $countries = Country::orderBy('name_fr')->get();
        
        return view('events.create', compact('countries', 'country'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();
        
        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        
        // Set organizer to current user
        $validated['organizer_id'] = Auth::id();
        
        // Handle online event logic
        if ($validated['is_online']) {
            $validated['location'] = null;
            $validated['address'] = null;
        } else {
            $validated['online_link'] = null;
        }
        
        $event = Event::create($validated);
        
        return redirect()
            ->route('country.event.show', [$event->country->slug, $event->id])
            ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $countries = Country::orderBy('name_fr')->get();
        
        return view('events.edit', compact('event', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEventRequest $request, Event $event)
    {
        $validated = $request->validated();
        
        // Update slug if title changed
        if ($validated['title'] !== $event->title) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $event->id);
        }
        
        // Handle online event logic
        if ($validated['is_online']) {
            $validated['location'] = null;
            $validated['address'] = null;
        } else {
            $validated['online_link'] = null;
        }
        
        $event->update($validated);
        
        return redirect()
            ->route('country.event.show', [$event->country->slug, $event->id])
            ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $countrySlug = $event->country->slug;
        $event->delete();
        
        return redirect()
            ->route('country.evenements', $countrySlug)
            ->with('success', 'Événement supprimé avec succès !');
    }
    
    /**
     * Generate unique slug for event
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Event::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
