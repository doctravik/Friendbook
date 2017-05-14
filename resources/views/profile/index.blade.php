@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container">
        @include('profile.partials.jumbotron')
        
        <div class="row">
            <div class="col-md-4">
                <friends-sidebar :user="{{ $user }}"
                    :friends="{{ $friends }}" 
                    :friends-count="{{ $user->selectFriendsCount() }}">
                </friends-sidebar> 
                <followers-sidebar :followers="{{ $followers }}"  
                    :followers-count="{{ $user->selectFollowersCount() }}">
                </followers-sidebar> 
            </div>
            <div class="col-md-8">
                @if(Auth::check() && Auth::user()->is($user))
                    <friend-requests-received></friend-requests-received>
                    <friend-requests-sent></friend-requests-sent>
                @endif                
            </div>
        </div>
    </div>
@endsection