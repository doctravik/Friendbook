<h2>Friends</h2>
<ul>
    @foreach($friends as $friend)
        <li>{{ $friend->getFullName() }}</li>
    @endforeach
</ul>