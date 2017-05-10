@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container">
        @if(Auth::check())
            @include('profile.partials.friend-button')
        @endif
        
        @include('profile.partials.friends')
        @include('profile.partials.followers')

        @if(Auth::check())
            @include('profile.partials.friend-requests-received')
            @include('profile.partials.friend-requests-sent')
        @endif
    </div>
@endsection