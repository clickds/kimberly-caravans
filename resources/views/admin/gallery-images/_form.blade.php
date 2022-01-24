<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @csrf
  @include('admin._partials.errors')

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name") }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position") }}" type="number" step="1" placeholder="Position">
  </div>

  <div>
    <label for="image">Image</label>
    <input name="image" type="file" value="{{ old('image', '') }}">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @lang('global.create')
    </button>
  </div>
</form>