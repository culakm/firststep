@if ($loop->even)
    <div style="background-color: red">{{ $key . ' - ' . $post['title'] }} neparny</div>
@else
    <div style="background-color: silver">{{ $key . ' - ' . $post['title'] }} parny</div>
@endif