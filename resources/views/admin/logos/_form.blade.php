<form method="POST" action="{{ $url }}" class="admin-form" enctype="multipart/form-data">
  @include('admin._partials.errors')

  @if ($logo->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $logo->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="external_url">
      External Url
    </label>
    <input name="external_url" value="{{ old("external_url", $logo->external_url) }}" type="text" placeholder="External Url">
    <a href="{{ route('admin.assets.index') }}" target="_blank" rel="noreferrer" class="underline text-endeavour">Assets</a>
  </div>

  <single-page-field field-name="page_id" :initial-page-id="@json($logo->page_id)"></single-page-field>

  <div>
    <label for="display_location">Display Location</label>
    <select name="display_location" id="display_location">
      @foreach ($displayLocations as $displayLocation)
        <option value="{{ $displayLocation }}"{{ old('display_location', $logo->display_location) === $displayLocation ? ' selected' : ''}}>
          {{ $displayLocation }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="image">Image</label>
    <input type="file" name="image">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($logo->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>