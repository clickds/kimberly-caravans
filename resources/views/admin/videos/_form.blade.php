<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($video->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

@include('admin._partials.site-checkboxes', [
    'sites' => $sites,
    'objectSiteIds' => $video->sites()->pluck('id')->toArray(),
  ])

  <div>
    <label for="type">
      Type
    </label>
    <select name="type">
      @foreach($types as $type)
        <option value="{{ $type }}"{{ old('type', $video->type) == $type ? ' selected' : '' }}>
          {{ $type }}
        </option>
      @endforeach
    </select>
  </div>

  <fieldset>
    <legend>Video Categories</legend>

    <div class="flex -mx-2">
      @foreach($categories as $category)
        <div class="mx-2 w-1/4">
          <label>
            <input type="checkbox" name="video_category_ids[]" value="{{ $category->id }}"
              {{ in_array($category->id, old('video_category_ids[]', $video->videoCategories()->pluck('id')->toArray())) ? ' checked' : '' }}
            >
            {{ $category->name }}
          </label>
        </div>
      @endforeach
    </div>
  </fieldset>

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old("title", $video->title) }}" type="text" placeholder="Title" required>
  </div>

  <div>
    <label for="excerpt">
      Excerpt
    </label>
    <textarea rows="3" name="excerpt">{{ old('excerpt', $video->excerpt) }}</textarea>
  </div>

  <div>
    <label for="embed_code">
      Embed Code
    </label>
    <textarea rows="3" name="embed_code" placeholder="Example: &lt;iframe width='560' height='315' src='https://www.youtube.com/embed/wB-r8OfIVVU' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen&gt;&lt;/iframe&gt;">{{ old('embed_code', $video->embed_code) }}</textarea>
  </div>

  @if ($video->hasMedia('image'))
    <p class="mb-1 font-bold" style="color:#4a5568;">Image</p>
    <div class="relative shadow border rounded p-3">
      <div>
        <img src="{{ $video->getFirstMediaUrl('image') }}">
      </div>
      <div>
        <a onclick="document.getElementById('delete_image').submit()" class="bg-red-500 hover:bg-red-700 cursor-pointer text-white font-bold py-2 px-4 rounded inline-flex items-center absolute top-0 right-0">X
        </a>
      </div>
    </div>
  @else
    <div>
      <label for="image">
        Image
      </label>
      <input name="image" type="file" value="{{ old('image', '') }}">
    </div>
    @if ($errors->has('image'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('image') }}</p>
    @endif
  @endif

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $video->published_at ? $video->published_at->format('Y-m-d') : '') }}" placeholder="Start Date">
  </div>

  <div>
    <label for="dealer_id">
      Dealer
    </label>
    <select name="dealer_id">
      <option value="">None</option>
      @foreach($dealers as $dealer)
        <option value="{{ $dealer->id }}"{{ old('dealer_id', $video->dealer_id) == $dealer->id ? ' selected' : '' }}>
          {{ $dealer->name }}
        </option>
      @endforeach
    </select>
  </div>

  @include('admin._partials.caravan-and-motorhome-ranges-checkboxes', [
    'caravanRanges' => $caravanRanges,
    'caravanRangeIds' => $video->caravanRanges->pluck('id')->toArray(),
    'motorhomeRanges' => $motorhomeRanges,
    'motorhomeRangeIds' => $video->motorhomeRanges->pluck('id')->toArray(),
  ])

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($video->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>


@if ($video->hasMedia('image'))
    <form method="POST" id="delete_image" action="{{ route('admin.video.destroyImage', [$video->getFirstMedia('image')->id]) }}">
        @method('DELETE')
        @csrf
    </form>
@endif