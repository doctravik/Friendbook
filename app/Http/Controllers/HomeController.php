<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    /**
     * @return void
     */
    public function __construct () {
        $this->middleware('auth');
    }

    /**
     * Show home page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $user = Auth::user();
        
        return view('profile.index', compact('user'))->with([
            'friends' => $user->friends()->take(9)->get(),
            'followers' => $user->followers()->take(9)->get()
        ]);
    }
}
