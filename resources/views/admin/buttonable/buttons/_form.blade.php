<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($button->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $button->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $button->position) }}" type="number" step="1" placeholder="Position" required>
  </div>

  <div>
    <label for="colour">Colour</label>
    <select name="colour">
      @foreach ($colours as $value => $label)
        <option value="{{ $value }}"{{ old('colour', $button->colour) == $value ? ' selected' : ''}}>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="external_url">
      External Url
    </label>
    <input name="external_url" value="{{ old("external_url", $button->external_url) }}" type="text" placeholder="External Url">
    <a href="{{ route('admin.assets.index') }}" target="_blank" rel="noreferrer" class="underline text-endeavour">Assets</a>
  </div>

  <single-page-field field-name="link_page_id" :initial-page-id="@json($button->link_page_id)"></single-page-field>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($button->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>