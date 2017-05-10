<h2>You sent requests to:</h2>
<ul>
    @foreach($user->friendRequestsSent as $friend)
        <li>{{ $friend->getFullName() }}</li>
    @endforeach
</ul>