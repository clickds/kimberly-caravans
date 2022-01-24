@include('admin._partials.errors')

<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form border">
  @csrf
  @if ($page->exists)
    @method('put')
  @endif
  @include('admin._partials.redirect-input')

  <site-page-fields :sites='@json($sites)'
    :initial-site-id='@json(old('site_id', $page->site_id))'
    :initial-page-id='@json(old('parent_id', $page->parent_id))'></site-page-fields>

  @if ($page->hasPageable())
    <input type="hidden" name="template" value="{{ $page->template }}" />
  @else
    <div>
      <label for="template">
        Template <span class="text-xs">used to change the layout of the page</span>
      </label>
      <select name="template" class="bg-gray-200 border border-gray-200 text-gray-700 px-3 rounded">
        @foreach($templates as $value => $name)
          <option value="{{ $value }}"{{ old('template', $page->template) == $value ? ' selected' : '' }}>{{ $name }}</option>
        @endforeach
      </select>
    </div>
  @endif

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $page->name) }}" required>
  </div>

  <div>
    <label for="meta_title">
      Meta Title
    </label>
    <input name="meta_title" type="text" placeholder="Meta Title" value="{{ old('meta_title', $page->meta_title) }}" required>
  </div>
  <div>
    <label for="meta_description">
      Meta Description
    </label>
    <textarea rows="3" name="meta_description" placeholder="Meta Description">{{ old('meta_description', $page->meta_description) }}</textarea>
  </div>

  <div>
    <label for="variety">
      Variety <span class="text-xs">used to fetch pages in global areas e.g. the footer and homepage strip</span>
    </label>
    <select name="variety" class="bg-gray-200 border border-gray-200 text-gray-700 px-3 rounded">
      @foreach($varieties as $variety)
        <option value="{{ $variety }}"{{ old('variety', $page->variety) == $variety ? ' selected' : '' }}>{{ $variety }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="video_banner_id">
      Video Banner
    </label>
    <select name="video_banner_id" class="bg-gray-200 border border-gray-200 text-gray-700 px-3 rounded">
      <option value="">None</option>
      @foreach($videoBanners as $videoBanner)
        <option value="{{ $videoBanner->id }}"{{ old('video_banner_id', $page->video_banner_id) == $videoBanner->id ? ' selected' : '' }}>
          {{ $videoBanner->name }}
        </option>
      @endforeach
    </select>
  </div>

  <fieldset>
    <legend>Image Banner</legend>
    @foreach ($imageBanners as $imageBanner)
      <div>
        <label>
          <div class="flex items-center">
            <img src="{{ $imageBanner->getFirstMediaUrl('image', 'thumb') }}">
            <div class="ml-2">
              <input type="checkbox" name="image_banner_ids[]" value="{{ $imageBanner->id }}"
              {{ in_array($imageBanner->id, old('image_banner_ids', $page->imageBanners()->pluck('id')->toArray())) ? ' checked' : '' }}
              >
              {{ $imageBanner->title }}
            </div>
          </div>
        </label>
      </div>
    @endforeach
  </fieldset>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $page->published_at) }}" placeholder="Published At">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $page->expired_at) }}" placeholder="Expired At">
  </div>

  <div>
    <input type="hidden" name="live" value="0">
    <label for="live">Live</label>
    <input id="live" name="live" type="checkbox" value="1"{{ old('live', $page->live) ? ' checked' : '' }}>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($page->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>
