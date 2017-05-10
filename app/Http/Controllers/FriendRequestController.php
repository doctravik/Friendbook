<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFriendRequest;

class FriendRequestController extends Controller {
    /**
     * @return void
     */
    public function __construct () {
        $this->middleware('auth')->only('store', 'destroy');
    }

   /**
     * Show received friend requests of the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function indexRequestsReceived ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();

        $friendRequestsReceived = $user->friendRequestsReceived()->get();

        return view('friends.requests.index', compact('user', 'friendRequestsReceived'));        
    }

   /**
     * Show friend requests sent by the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function indexRequestsSent ($userSlug) {
        $user = User::findBySlug($userSlug)->firstOrFail();

        $friendRequestsSent = $user->friendRequestsSent()->get();

        return view('friends.requests.index', compact('user', 'friendRequestsSent'));        
    }

    /**
     * Store friend request in db.
     * 
     * @param  User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (User $user) {
        auth()->user()->sendFriendRequestTo($user);
        auth()->user()->follow($user);

        return response()->json([], 200);
    }

    /**
     * Delete friend request in db.
     * 
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy (User $user) {
        auth()->user()->cancelPendingFriendship($user);

        return response()->json([], 200);
    }
}
