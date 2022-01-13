@if ($errors->any())
    <div class="mt-2 mb-2">
        @foreach ($errors->all() as $error)
            <div class="alert bg-danger">{{ $error }}</div>
        @endforeach
    </div>
@endif