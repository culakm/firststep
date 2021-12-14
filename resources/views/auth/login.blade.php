@extends('layouts.app')
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label>E-mail</label>
        <input name="email" value="{{ old('email') }}" required 
            class="form-control {{ $errors->has('email') ? ' is-invalid':'' }}">

        @if ($errors->has('email'))
            <span class="invalid_feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label>Password</label>
        <input name="password" required type="password"
            class="form-control {{ $errors->has('password') ? ' is-invalid':'' }}
        >

        @if ($errors->has('password'))
            <span class="invalid_feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="remember"
             value="{{ old('remember') ? 'checked' : '' }}">
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>
    </div>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>
@endsection('content')