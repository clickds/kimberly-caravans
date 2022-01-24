<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($dealer->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $dealer->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="feed_location_code">
      Feed Location Code
    </label>
    <input name="feed_location_code" value="{{ old("feed_location_code", $dealer->feed_location_code) }}" type="text" placeholder="Feed Location Code">
  </div>

  @include('admin._partials.site-select', [
    'sites' => $sites,
    'fieldName' => 'site_id',
    'currentSiteId' => $dealer->site_id,
  ])

  <div>
    <fieldset class="border border-gray-300 p-4">
      <legend>Dealer Type</legend>
      <div class="flex flex-row space-x-5">
        <div>
          <label for="is_branch">Branch</label>
          <input type="hidden" name="is_branch" value="0">
          <input id="is_branch" name="is_branch" type="checkbox" value="1"{{ old('is_branch', $dealer->is_branch) ? ' checked' : '' }}>
        </div>

        <div>
          <label for="is_servicing_center">Servicing Center</label>
          <input type="hidden" name="is_servicing_center" value="0">
          <input id="is_servicing_center" name="is_servicing_center" type="checkbox" value="1"{{ old('is_servicing_center', $dealer->is_servicing_center) ? ' checked' : '' }}>
        </div>
      </div>
    </fieldset>
  </div>

  <div>
  <fieldset class="border border-gray-300 p-4">
      <legend>Available to View</legend>
      <div class="flex flex-row space-x-5">
        <div>
          <label for="can_view_motorhomes">Motorhomes</label>
          <input type="hidden" name="can_view_motorhomes" value="0">
          <input id="can_view_motorhomes" name="can_view_motorhomes" type="checkbox" value="1"{{ old('can_view_motorhomes', $dealer->can_view_motorhomes) ? ' checked' : '' }}>
        </div>

        <div>
          <label for="can_view_caravans">Caravans</label>
          <input type="hidden" name="can_view_caravans" value="0">
          <input id="can_view_caravans" name="can_view_caravans" type="checkbox" value="1"{{ old('can_view_caravans', $dealer->can_view_caravans) ? ' checked' : '' }}>
        </div>
      </div>
    </fieldset>
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $dealer->position) }}" type="number" step="1" placeholder="Position" required>
  </div>

  <div>
    <label for="video_embed_code">
      Video Embed Code
    </label>
    <input name="video_embed_code" value="{{ old("video_embed_code", $dealer->video_embed_code) }}" type="text" placeholder="Video Embed Code">
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Facilities"
    name="facilities"
    initial-value="{{ old('facilities', $dealer->facilities) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Servicing Centre"
    name="servicing_centre"
    initial-value="{{ old('servicing_centre', $dealer->servicing_centre) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div>
    <label for="latitude">
      Latitude
    </label>
    <input name="latitude" value="{{ old("latitude", $location->latitude) }}" type="text" placeholder="Latitude" required>
  </div>

  <div>
    <label for="longitude">
      Longitude
    </label>
    <input name="longitude" value="{{ old("longitude", $location->longitude) }}" type="text" placeholder="Longitude" required>
  </div>

  <div>
    <label for="google_maps_url">
      Google Maps URL
    </label>
    <input name="google_maps_url" value="{{ old("google_maps_url", $location->google_maps_url) }}" type="text" placeholder="Google Maps URL" required>
  </div>

  <div>
    <label for="line_1">
      Address Line 1
    </label>
    <input name="line_1" value="{{ old("line_1", $location->line_1) }}" type="text" placeholder="Address Line 1" required>
  </div>

  <div>
    <label for="line_2">
      Address Line 2
    </label>
    <input name="line_2" value="{{ old("line_2", $location->line_2) }}" type="text" placeholder="Address Line 2">
  </div>

  <div>
    <label for="town">
      Town
    </label>
    <input name="town" value="{{ old("town", $location->town) }}" type="text" placeholder="Town">
  </div>

  <div>
    <label for="county">
      County
    </label>
    <input name="county" value="{{ old("county", $location->county) }}" type="text" placeholder="County">
  </div>

  <div>
    <label for="postcode">
      Postcode
    </label>
    <input name="postcode" value="{{ old("postcode", $location->postcode) }}" type="text" placeholder="Postcode">
  </div>

  <div>
    <label for="phone">
      Phone
    </label>
    <input name="phone" value="{{ old("phone", $location->phone) }}" type="text" placeholder="Phone">
  </div>

  <div>
    <label for="fax">
      Fax
    </label>
    <input name="fax" value="{{ old("fax", $location->fax) }}" type="text" placeholder="Fax">
  </div>

  <div>
    <label for="website_url">
      Website URL (mainly used for international dealers)
    </label>
    <input name="website_url" value="{{ old('website_url', $dealer->website_url) }}" type="text" placeholder="Website URL">
  </div>

  <div>
    <label for="website_link_text">
      Website Link Text
    </label>
    <input name="website_link_text" value="{{ old('website_link_text', $dealer->website_link_text) }}" type="text" placeholder="Website Link Text">
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Opening Hours"
    name="opening_hours"
    initial-value="{{ old('opening_hours', $location->opening_hours) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  @include('admin._partials.caravan-and-motorhome-ranges-checkboxes', [
    'caravanRanges' => $caravanRanges,
    'caravanRangeIds' => $dealer->caravanRanges->pluck('id')->toArray(),
    'motorhomeRanges' => $motorhomeRanges,
    'motorhomeRangeIds' => $dealer->motorhomeRanges->pluck('id')->toArray(),
  ])

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', ($dealer->published_at ? $dealer->published_at->toDateString() : '')) }}" placeholder="Published At">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $dealer->expired_at) }}" placeholder="Expired At">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($dealer->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>