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
        $user = User::findBySlug($userSlug)->withCount('followers')->firstOrFail();

        return view('profile.index', compact('user'))->with([
            'friends' => $user->friends()->take(9)->get(),
            'followers' => $user->followers()->take(9)->get()
        ]);
    }
}
