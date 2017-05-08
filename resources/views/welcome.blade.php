@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
    <div class="container">
        <div class="jumbotron">
            <h1>Welcome to Fakebook</h1>
            <p><a class="btn btn-primary btn-lg" href="{{ url('/login') }}" role="button">Login</a></p>
        </div>
    </div>
@endsection