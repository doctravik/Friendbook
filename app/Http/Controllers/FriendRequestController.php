<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use App\Http\Requests\StoreFriendRequest;

class FriendRequestController extends Controller {
    /**
     * @return void
     */
    public function __construct () {
        $this->middleware('auth');
    }

   /**
     * Select received friend requests of the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\Response
     */
    public function indexRequestsReceived () {
        $requests = Auth::user()->friendRequestsReceived()->get();

        return fractal()
            ->collection($requests)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

   /**
     * Select friend requests sent by the user.
     * 
     * @param string $userSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexRequestsSent () {
        $requests = Auth::user()->friendRequestsSent()->get();

        return fractal()
            ->collection($requests)
            ->transformWith(new UserTransformer)
            ->toArray();    
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
