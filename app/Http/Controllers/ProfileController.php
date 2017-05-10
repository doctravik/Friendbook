<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    /**
     * Show profile page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();
        
        $user->load(['followers']);

        return view('profile.index', compact('user'))->withFriends($user->friends());
    }
}
