<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')
  @if ($panel->exists)
    @method('PUT')
  @endif
  @csrf
  @include('admin._partials.redirect-input')

  <div>
    <label for="area_id">Area</label>
    <select name="area_id" id="area_id">
      @foreach ($areas as $area)
        <option value="{{ $area->id }}"{{ $area->id == old('area_id', $panel->area_id) ? ' selected': '' }}>
          {{ $area->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="name">
        Name
    </label>
    <input name="name" type="text" value="{{ old('name', $panel->name) }}" placeholder="Name" required>
  </div>

  <div>
    <label for="heading">Heading</label>
    <input type="text" name="heading" value="{{ old('heading', $panel->heading) }}" />
  </div>

  <div>
    <label for="heading_type">Heading Type</label>
    <select name="heading_type">
      @foreach($headingTypes as $headingType)
        <option value="{{ $headingType }}"{{ old('heading_type', $panel->heading_type) == $headingType ? ' selected' : '' }}>
          {{ $headingType }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="vertical_positioning">Vertical Positioning</label>
    <select name="vertical_positioning">
      @foreach($verticalPositions as $value => $name)
        <option value="{{ $value }}"{{ old('vertical_positioning', $panel->vertical_positioning) == $value ? ' selected' : '' }}>
          {{ $name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="text_alignment">Text Alignment</label>
    <select name="text_alignment">
      @foreach($textAlignments as $value => $name)
        <option value="{{ $value }}"{{ old('text_alignment', $panel->text_alignment) == $value ? ' selected' : '' }}>
          {{ $name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input type="number" name="position" value="{{ old('position', $panel->position) }}" />
  </div>

  <page-panel
    csrf-token='{{ csrf_token() }}'
    :site-id='{{ $siteId }}'
    initial-content='{{ old('content', $panel->content) }}'
    initial-html-content='{{ old('content', $panel->html_content) }}'
    :initial-featureable-id='@json(old('featureable_id', $panel->featureable_id))'
    :initial-featureable-type='@json(old('featureable_type', $panel->featureable_type))'
    initial-featured-image-content='{{ old('featured_image_content', $panel->featured_image_content) }}'
    initial-featured-image-alt-text='{{ old('featured_image_alt_text', $panel->featured_image_alt_text) }}'
    initial-image-alt-text='{{ old('image_alt_text', $panel->image_alt_text) }}'
    :initial-page-id='@json(old('page_id', $panel->page_id))'
    initial-external-url='{{ old('external_url', $panel->external_url) }}'
    initial-read-more-content='{{ old('read_more_content', $panel->read_more_content) }}'
    initial-type='{{ old('type', $panel->type) }}'
    initial-vehicle-type='{{ old('vehicle_type', $panel->vehicle_type) }}'
    initial-overlay-position='{{ old('overlay_position', $panel->overlay_position)}}'
    :initial-special-offer-ids='@json(old('special_offer_ids', $panel->specialOffers()->pluck('id')->toArray()))'
    :overlay-positions='@json($overlayPositions)'
    :types='@json($types)'
    :vehicle-types='@json($vehicleTypes)'
    assets-page-url="{{ route('admin.assets.index') }}">
  </page-panel>

  <!-- TODO featureable_id and featureable_type polymorphic -->
  <!-- TODO video -->
  <!-- TODO form -->

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $panel->published_at) }}" placeholder="Published At">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $panel->expired_at) }}" placeholder="Expired At">
  </div>

  <div>
    <input type="hidden" name="live" value="0">
    <label for="live">Live</label>
    <input id="live" name="live" type="checkbox" value="1"{{ old('live', $panel->live) ? ' checked' : '' }}>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($panel->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>