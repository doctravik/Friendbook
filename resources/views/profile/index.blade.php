@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container">
        <div class="jumbotron">
            <h1>{{ $user->getFullName() }}</h1>

            @if (Auth::check() && !Auth::user()->is($user))
                <friend-buttons 
                    :current-user="{{ Auth::user() }}"
                    :profile-user-id="{{ $user->id }}"
                    :friend-button-state="{{ Auth::user()->getFriendButtonStateFor($user) }}">
                </friend-buttons>
            @endif
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <friends :friends="{{ $friends }}" 
                    :friends-count="{{ $user->selectFriendsCount() }}">
                </friends> 
                <followers :followers="{{ $followers }}"  
                    :followers-count="{{ $user->selectFollowersCount() }}">
                </followers> 
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