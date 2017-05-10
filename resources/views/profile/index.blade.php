@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container">
        @if(Auth::check())
            @include('profile.partials.friend-button')
        @endif
        
        <div class="row">
            <div class="col-md-4">
                @include('profile.partials.friends')
                @include('profile.partials.followers')
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