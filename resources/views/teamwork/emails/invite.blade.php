You have been invited to join team {{ $team->name }}.<br>
Click here to join: <a
    href="{{ route('register', ['invitation_token' => $invite->accept_token]) }}">{{ route('register', ['invitation_token' => $invite->accept_token]) }}</a>
