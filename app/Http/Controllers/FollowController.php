<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowController extends Controller {
    /**
     * @return void
     */
    public function __construct () {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show followers of the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function index ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();

        $followers = $user->followers()->get();

        return view('followers.index', compact('user', 'followers'));
    }

    /**
     * Store subscription in db.
     * 
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (User $user) {
        auth()->user()->follow($user);

        return response()->json([], 200);
    }

    /**
     * Cancel subscription.
     *  
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy (User $user) {
        auth()->user()->unfollow($user);

        return response()->json([], 200);
    }
}
