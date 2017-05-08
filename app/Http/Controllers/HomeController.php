<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('profile.index');
    }
}
