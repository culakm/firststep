@if (!isset($show) || $show)
    <span class="badge rounded-pill bg-{{ $another_parameter ?? 'success' }}">
        {{ $slot }}
    </span>
@endif