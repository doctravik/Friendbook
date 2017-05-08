<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowingController extends Controller {
    /**
     * Show followers of the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function index ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();

        $followedUsers = $user->followedUsers()->get();
        
        return view('following.index', compact('user', 'followedUsers'));
    }
}
