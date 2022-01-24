<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
    @include('admin._partials.errors')
    @csrf

    @include('admin._partials.redirect-input')

    <div>
      <label for="name">
        Name
      </label>
      <input name="name" value="{{ old("name", $caravanRange->name) }}" type="text" placeholder="Name" required>
    </div>

    <div>
      <input type="hidden" name="prepend_range_name_to_model_names" value="0">
      <label for="prepend_range_name_to_model_names">Show the range name before all model names?</label>
      <input id="prepend_range_name_to_model_names" name="prepend_range_name_to_model_names" type="checkbox" value="1"{{ old('prepend_range_name_to_model_names', $caravanRange->prepend_range_name_to_model_names) ? ' checked' : '' }}>
    </div>

    <div>
      <label for="position">
        Position
      </label>
      <input name="position" value="{{ old("position", $caravanRange->position) }}" type="number" step="1" placeholder="Position" required>
    </div>

    @include('admin._partials.site-checkboxes', [
      'sites' => $sites,
      'objectSiteIds' => $caravanRange->sites()->pluck('id')->toArray(),
    ])

    @include('admin._partials.colour-input', [
      'label' => 'Primary Theme Colour',
      'name' => 'primary_theme_colour',
      'colours' => $primaryThemeColours,
      'value' => old('primary_theme_colour', $caravanRange->primary_theme_colour)
    ])
    @include('admin._partials.colour-input', [
      'label' => 'Secondary Theme Colour',
      'name' => 'secondary_theme_colour',
      'colours' => $secondaryThemeColours,
      'value' => old('secondary_theme_colour', $caravanRange->secondary_theme_colour)
    ])

    <div>
      <label for="overview">
        Overview
      </label>
      <textarea rows="3" name="overview">{{ old('overview', $caravanRange->overview) }}</textarea>
    </div>

    <div>
      @if ($media = $caravanRange->getFirstMedia('mainImage'))
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
        <input type="checkbox" name="exclusive" value="1"{{ $caravanRange->exclusive ? ' checked' : ''}}> Exclusive
      </label>
    </div>

    <div>
      <input type="hidden" name="live" value="0">
      <label>
        <input type="checkbox" name="live" value="1"{{ $caravanRange->live ? ' checked' : ''}}> Live
      </label>
    </div>

    <div class="flex items-center justify-between">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
        @lang('global.clone')
      </button>
    </div>
  </form>