<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($usefulLinkCategory->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div class="mb-6">
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $usefulLinkCategory->name) }}" type="text" placeholder="Name" required>
  </div>

  <div class="mb-6">
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $usefulLinkCategory->position) }}" type="number" placeholder="Position" required>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($usefulLinkCategory->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>