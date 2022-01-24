<form method="POST" action="{{ $url }}" class="admin-form border">
  @include('admin._partials.errors')

  @if ($navigationItem->exists)
    @method('put')
  @endif

  @csrf

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $navigationItem->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="parent_id">Parent</label>
    <select name="parent_id">
      <option value="">None</option>
      @foreach ($navigationItems as $potentialParent)
        <option value="{{ $potentialParent->id }}"
          {{ old('parent_id', $navigationItem->parent_id) == $potentialParent->id ? ' selected' : '' }}>
          {{ $potentialParent->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <single-page-field :initial-page-id='@json(old('page_id', $navigationItem->page_id))' :site-id='{{ $navigation->site_id }}'></single-page-field>
  </div>
  <div>
    <label for="query_parameters">
      Query Parameters (applied to the end of a page URL)
    </label>
    <input name="query_parameters" value="{{ old("query_parameters", $navigationItem->query_parameters) }}" type="text" placeholder="E.g. ?manufacturer=Adria&axles=Single">
  </div>

  <div>
    <label for="external_url">
      External URL (Page will take priority if set)
    </label>
    <input name="external_url" value="{{ old("external_url", $navigationItem->external_url) }}" type="text" placeholder="External URL">
  </div>

  <fieldset>
    <legend>Background Colour (Only applies to main navigation items on desktop)</legend>

    <div class="flex flex-wrap -mx-2">
      @foreach ($backgroundColours as $colour => $humanisedColour)
        @php
          $checked = false;
          if (!$navigationItem->background_colour && $colour === array_keys(App\Models\NavigationItem::BACKGROUND_COLOURS)[0]) {
            $checked = true;
          }
          if (old('background_colour', $navigationItem->background_colour) == $colour) {
            $checked = true;
          }
        @endphp
        <div class="w-1/4 px-2">
          <label>
            <span class="h-4 w-4 border border-solid border-black inline-block bg-{{ $colour }}">
            </span>
            <input
              type="radio"
              name="background_colour"
              value="{{ $colour }}"
              {{ $checked ? 'checked' : '' }}
            >
            {{ $humanisedColour }}
          </label>
        </div>
      @endforeach
    </div>
  </fieldset>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($navigationItem->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>