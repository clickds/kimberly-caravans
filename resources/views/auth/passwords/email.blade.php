@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-xs">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border">
      <h1 class="text-gray-700 text-xl mb-4">{{ __('Reset Password') }}</h1>

      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('E-Mail Address') }}</label>

          <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

          @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-4">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            {{ __('Send Password Reset Link') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
