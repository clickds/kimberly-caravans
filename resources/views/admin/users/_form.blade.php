<form method="post" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @if ($user->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.errors')

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $user->name) }}" required>
  </div>

  <div>
    <label for="email">Email</label>
    <input name="email" type="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
  </div>

  <div>
    <label for="password">Password</label>
    <input name="password" type="password" placeholder="Password" value="{{ old('password') }}">
  </div>

  <div>
    <input type="hidden" name="super" value="0">
    @if (\Auth::user()->super)
    <label for="super">Super</label>
    <input id="super" name="super" type="checkbox" value="1"{{ old('super', $user->super) ? ' checked' : '' }}>
    @endif
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($user->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>