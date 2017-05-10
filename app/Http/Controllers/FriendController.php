<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FriendController extends Controller {
    /**
     * @return void
     */
    public function __construct () {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show friends of the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function index ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();

        $friends = $user->friends()->get();

        return view('friends.index', compact('user', 'friends'));        
    }

    /**
     * Accept friend request.
     * 
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (User $user) {
        auth()->user()->acceptFriendRequest($user);

        return response()->json([], 200);
    }

    /**
     * Reject friend request.
     * 
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy (User $user) {
        auth()->user()->cancelAcceptedFriendship($user);

        return response()->json([], 200);     
    }
}
