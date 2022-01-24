<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($testimonial->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $testimonial->name) }}" type="text" placeholder="Title" required>
    @if ($errors->has('name'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('name') }}</p>
    @endif
  </div>

  <div>
    <label for="content">
      Content
    </label>
    <textarea rows="3" name="content" required placeholder="Text Here">{{ old('content', $testimonial->content) }}</textarea>
  </div>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $testimonial->published_at ? $testimonial->published_at->format('Y-m-d') : date('Y-m-d')) }}" placeholder="Published At">
  </div>

  @include('admin._partials.site-checkboxes', [
    'sites' => $sites,
    'objectSiteIds' => $testimonial->sites()->pluck('id')->toArray(),
  ])

  <div>
    <label for="position">
      Position
    </label>
    <input type="number" name="position" value="{{ old('position', $testimonial->position ?? 0) }}" step="1" placeholder="Optional">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($testimonial->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>