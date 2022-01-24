<form method="POST" action="{{ $url }}" class="admin-form" enctype="multipart/form-data">
  @include('admin._partials.errors')

  @if ($siteSetting->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="name">
      Name
    </label>
    <select name="name" id="name" required>
      @foreach ($siteSettingNames as $siteSettingName)
        <option value="{{ $siteSettingName }}"{{ old('name', $siteSetting->name) === $siteSettingName ? ' selected' : ''}}>
          {{ $siteSettingName }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="description">
      Description
    </label>
    <input name="description" value="{{ old("description", $siteSetting->description) }}" type="text" placeholder="Description">
  </div>

  <div>
    <label for="value">
      Value
    </label>
    <input name="value" value="{{ old("value", $siteSetting->value) }}" type="text" placeholder="Value" required>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($siteSetting->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>