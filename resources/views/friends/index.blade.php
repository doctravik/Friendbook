@extends('layouts.app')
@section('title', 'Friends')
@section('content')
    <div class="container">
        @include('profile.partials.jumbotron')
        
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <friends-view :friends="{{ $friends }}"
                    :friends-count="{{ $user->selectFriendsCount() }}"
                    :profile-user="{{ $user }}"
                    :current-user="{{ Auth::check() ? Auth::user() : null }}">
                </friends-view>
            </div>
        </div>
    </div>
@endsection