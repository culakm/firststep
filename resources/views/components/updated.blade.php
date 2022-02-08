<p class="text-muted">
    {{ empty(trim($slot)) ? 'Added ' : $slot}} {{ $date->diffForHumans() }}
    @if (isset($name))
        @if (isset($user_id))
            by <a href="{{ route('users.show', ['user' => $user_id]) }}">{{ $name }}</a>
        @else
            by {{ $name }} lalal
        @endif
    @endif
    
</p>