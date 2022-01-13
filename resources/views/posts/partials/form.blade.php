<div class="form-group">
    <label for="title">Title</label>
    <input id="title" type="text" name="title" class="form-control" value="{{ old('title', optional($post ?? null)->title) }}">
</div>
{{-- toto je natvrdo definovany error --}}
@error('title')
    <div classes="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="content">Content</label>
    <textarea id="content" name="content" class="form-control mt-2 mb-2" cols="30" rows="10" >{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
{{-- toto je natvrdo definovany error --}}
@error('content')
    <div classes="alert alert-danger">{{ $message }}</div>
@enderror
{{-- toto je error z components errors --}}
@errors @enderrors