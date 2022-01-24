<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($eventLocation->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $eventLocation->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="address_line_1">
      Address Line 1
    </label>
    <input name="address_line_1" value="{{ old("address_line_1", $eventLocation->address_line_1) }}" type="text" placeholder="Address Line 1">
  </div>
  <div>
    <label for="address_line_2">
      Address Line 2
    </label>
    <input name="address_line_2" value="{{ old("address_line_2", $eventLocation->address_line_2) }}" type="text" placeholder="Address Line 2">
  </div>

  <div>
    <label for="town">
      Town
    </label>
    <input name="town" value="{{ old("town", $eventLocation->town) }}" type="text" placeholder="Town">
  </div>

  <div>
    <label for="county">
      County
    </label>
    <input name="county" value="{{ old("county", $eventLocation->county) }}" type="text" placeholder="County">
  </div>

  <div>
    <label for="postcode">
      Postcode
    </label>
    <input name="postcode" value="{{ old("postcode", $eventLocation->postcode) }}" type="text" placeholder="Postcode">
  </div>

  <div>
    <label for="phone">
      Phone
    </label>
    <input name="phone" value="{{ old("phone", $eventLocation->phone) }}" type="text" placeholder="Phone">
  </div>

  <div>
    <label for="fax">
      Fax
    </label>
    <input name="fax" value="{{ old("fax", $eventLocation->fax) }}" type="text" placeholder="Fax">
  </div>

  <div>
    <label for="latitude">
      Latitude
    </label>
    <input name="latitude" value="{{ old("latitude", $eventLocation->latitude) }}" type="text" placeholder="Latitude" required>
  </div>

  <div>
    <label for="longitude">
      Longitude
    </label>
    <input name="longitude" value="{{ old("longitude", $eventLocation->longitude) }}" type="text" placeholder="Latitude" required>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($eventLocation->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>