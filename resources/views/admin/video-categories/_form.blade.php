<form method="post" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @if ($video_category->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.errors')

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $video_category->name) }}" required>
    @if ($errors->has('name'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('name') }}</p>
    @endif
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" type="number" step="1" placeholder="Position" value="{{ old('position', $video_category->position) }}">
    @if ($errors->has('position'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('position') }}</p>
    @endif
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($video_category->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>