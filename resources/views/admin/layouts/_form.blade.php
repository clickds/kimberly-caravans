<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($layout->exists)
    @method('put')
  @endif

  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Code
    </label>
    <input name="code" type="text" placeholder="Code" value="{{ old("code", $layout->code) }}" required>
  </div>
  <div>
    <label for="holder">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old("name", $layout->name) }}" required>
  </div>
  <div>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($layout->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>