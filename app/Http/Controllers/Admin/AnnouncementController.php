<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementStatusChanged;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Announcement::with('user');

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher les annonces en attente
            $query->pending();
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $announcements = $query->latest()->paginate(20);

        // Statistiques
        $stats = [
            'pending' => Announcement::pending()->count(),
            'active' => Announcement::active()->count(),
            'refused' => Announcement::refused()->count(),
            'total' => Announcement::count()
        ];

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('user');
        return view('admin.announcements.show', compact('announcement'));
    }

    public function approve(Announcement $announcement)
    {
        $announcement->update(['status' => 'active']);

        // Envoyer un email de notification
        if (class_exists(AnnouncementStatusChanged::class)) {
            Mail::to($announcement->user->email)
                ->send(new AnnouncementStatusChanged($announcement, 'approved'));
        }

        return redirect()->back()
            ->with('success', "L'annonce a été approuvée.");
    }

    public function refuse(Request $request, Announcement $announcement)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $announcement->update([
            'status' => 'refused',
            'refusal_reason' => $request->reason
        ]);

        // Envoyer un email de notification
        if (class_exists(AnnouncementStatusChanged::class)) {
            Mail::to($announcement->user->email)
                ->send(new AnnouncementStatusChanged($announcement, 'refused', $request->reason));
        }

        return redirect()->back()
            ->with('success', "L'annonce a été refusée.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,refuse,delete',
            'announcement_ids' => 'required|array',
            'announcement_ids.*' => 'exists:announcements,id'
        ]);

        $announcements = Announcement::whereIn('id', $request->announcement_ids)->get();

        switch ($request->action) {
            case 'approve':
                foreach ($announcements as $announcement) {
                    $announcement->update(['status' => 'active']);
                }
                $message = count($announcements) . " annonce(s) approuvée(s).";
                break;

            case 'refuse':
                foreach ($announcements as $announcement) {
                    $announcement->update(['status' => 'refused']);
                }
                $message = count($announcements) . " annonce(s) refusée(s).";
                break;

            case 'delete':
                foreach ($announcements as $announcement) {
                    // Supprimer les images
                    if ($announcement->images) {
                        foreach ($announcement->images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                    $announcement->delete();
                }
                $message = count($announcements) . " annonce(s) supprimée(s).";
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}