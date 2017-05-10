<div class="jumbotron">
    <h1>{{ $user->getFullName() }}</h1>
    @if (Auth::check() && !Auth::user()->is($user))
        <friend-buttons 
            :current-user-id="{{ Auth::user()->id }}"
            :profile-user-id="{{ $user->id }}"
            :friendship="{{ json_encode(Auth::user()->selectFriendshipWith($user)) }}">
        </friend-buttons>
    @endif
</div>



