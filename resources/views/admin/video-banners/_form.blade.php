<form method="post" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @if ($videoBanner->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.errors')

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $videoBanner->name) }}" required>
  </div>

  <div>
    <label for="mp4">mp4 File</label>
    <input name="mp4" type="file" value="{{ old('mp4', '') }}">
  </div>

  <div>
    <label for="webm">Webm File</label>
    <input name="webm" type="file" value="{{ old('webm', '') }}">
  </div>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $videoBanner->published_at ? $videoBanner->published_at->format('Y-m-d') :'') }}">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $videoBanner->expired_at ? $videoBanner->expired_at->format('Y-m-d') : '' ) }}">
  </div>

  <div>
    <input type="hidden" name="live" value="0">
    <label>
      <input type="checkbox" name="live" value="1"{{ $videoBanner->live ? ' checked' : ''}}> Live
    </label>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($videoBanner->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>