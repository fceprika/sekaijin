<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($name)
    {
        $user = User::where('name', $name)->first();
        
        if (!$user) {
            abort(404, 'Membre introuvable');
        }
        
        return view('profile.public', compact('user'));
    }
}