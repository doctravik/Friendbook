<h2>You have friend requests from:</h2>
<ul>
    @foreach($user->friendRequestsReceived as $friend)
        <li>{{ $friend->getFullName() }}</li>
    @endforeach
</ul>