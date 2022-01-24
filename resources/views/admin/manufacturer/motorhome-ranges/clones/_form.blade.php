<form method="POST" action="{{ $url }}" class="admin-form" enctype="multipart/form-data">
    @include('admin._partials.errors')
    @csrf

    @include('admin._partials.redirect-input')

    <div>
      <label for="name">
        Name
      </label>
      <input name="name" value="{{ old("name", $motorhomeRange->name) }}" type="text" placeholder="Name" required>
    </div>

    <div>
      <input type="hidden" name="prepend_range_name_to_model_names" value="0">
      <label for="prepend_range_name_to_model_names">Show the range name before all model names?</label>
      <input id="prepend_range_name_to_model_names" name="prepend_range_name_to_model_names" type="checkbox" value="1"{{ old('prepend_range_name_to_model_names', $motorhomeRange->prepend_range_name_to_model_names) ? ' checked' : '' }}>
    </div>

    <div>
      <label for="position">
        Position
      </label>
      <input name="position" value="{{ old("position", $motorhomeRange->position) }}" type="number" step="1" placeholder="Position" required>
    </div>

    @include('admin._partials.site-checkboxes', [
      'sites' => $sites,
      'objectSiteIds' => $motorhomeRange->sites()->pluck('id')->toArray(),
    ])

    @include('admin._partials.colour-input', [
      'label' => 'Primary Theme Colour',
      'name' => 'primary_theme_colour',
      'colours' => $primaryThemeColours,
      'value' => old('primary_theme_colour', $motorhomeRange->primary_theme_colour)
    ])
    @include('admin._partials.colour-input', [
      'label' => 'Secondary Theme Colour',
      'name' => 'secondary_theme_colour',
      'colours' => $secondaryThemeColours,
      'value' => old('secondary_theme_colour', $motorhomeRange->secondary_theme_colour)
    ])

    <div>
      <label for="overview">
        Overview
      </label>
      <textarea rows="3" name="overview">{{ old('overview', $motorhomeRange->overview) }}</textarea>
    </div>

    <div>
      @if ($media = $motorhomeRange->getFirstMedia('mainImage'))
        <img src="{{ $media->getUrl('thumb') }}" alt="{{ $media->name }}">
      @endif
      <label for="image">
        Image
      </label>
      <input name="image" type="file" value="{{ old('image', '') }}">
    </div>

    <div>
      <input type="hidden" name="exclusive" value="0">
      <label>
        <input type="checkbox" name="exclusive" value="1"{{ $motorhomeRange->exclusive ? ' checked' : ''}}> Exclusive
      </label>
    </div>

    <div>
      <input type="hidden" name="live" value="0">
      <label>
        <input type="checkbox" name="live" value="1"{{ $motorhomeRange->live ? ' checked' : ''}}> Live
      </label>
    </div>

    <div class="flex items-center justify-between">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
        @lang('global.clone')
      </button>
    </div>
  </form>