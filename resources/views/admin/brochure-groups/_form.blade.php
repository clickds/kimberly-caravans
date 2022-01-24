<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($brochure_group->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old('name', $brochure_group->name) }}" type="text" placeholder="name" required>
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old('position', $brochure_group->position) }}" type="number">
  </div>


  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($brochure_group->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>

</form>
