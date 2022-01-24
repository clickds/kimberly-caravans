<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($cta->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="type">Type</label>
    <select name="type" id="type">
      @foreach ($types as $type)
        <option value="{{ $type }}"{{ old('type', $cta->type) == $type ? ' selected' : ''}}>
          {{ $type }}
        </option>
      @endforeach
    </select>
  </div>

  <site-page-fields :sites='@json($sites)'
    page-field-name="page_id"
    page-field-label="Page"
    :initial-site-id='@json(old('site_id', $cta->site_id))'
    :initial-page-id='@json(old('page_id', $cta->page_id))'></site-page-fields>

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old("title", $cta->title) }}" type="text" placeholder="Title" required>
  </div>

  <div>
    <label for="excerpt_text">
      Excerpt
    </label>
    <textarea rows="3" name="excerpt_text">{{ old('excerpt_text', $cta->excerpt_text) }}</textarea>
  </div>

  <div>
    <label for="link_text">
      Link Text <span class="text-xs">Not required for news and info lander ctas</span>
    </label>
    <input name="link_text" value="{{ old("link_text", $cta->link_text) }}" type="text" placeholder="Link Text">
  </div>

  @if ($cta->hasMedia('image'))
    <p class="mb-1 font-bold" style="color:#4a5568;">Background Image</p>
    <div class="relative shadow border rounded p-3">
      <div>
        <img src="{{ $cta->getFirstMediaUrl('image') }}">
      </div>
    </div>
  @endif
  <div>
    <label for="image">
      Image
    </label>
    <input name="image" type="file" value="{{ old('image', '') }}">
  </div>
  @if ($errors->has('image'))
    <p class="text-red-500 text-xs italic">{{ $errors->first('image') }}</p>
  @endif

  <div>
    <label for="position">Position</label>
    <input type="number" name="position" id="position" value="{{ old('position', $cta->position) }}">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($cta->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
