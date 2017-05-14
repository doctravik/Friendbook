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