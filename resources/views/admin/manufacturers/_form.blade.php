<form method="post" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @if ($manufacturer->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.errors')

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $manufacturer->name) }}" required>
    @if ($errors->has('name'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('name') }}</p>
    @endif
  </div>

  <div>
    <input type="hidden" name="exclusive" value="0">
    <label for="exclusive">Exclusive</label>
    <input type="checkbox" name="exclusive" value="1" {{ old('exclusive', $manufacturer->exclusive) == "1" ? "checked" : "" }}>
  </div>

  <div>
    <input type="hidden" name="link_directly_to_stock_search" value="0">
    <label for="link_directly_to_stock_search">Link Directly To Stock Search <small>(select this to link directly to the stock search instead of the standard manufacturer/range pages)</small></label>
    <input type="checkbox" name="link_directly_to_stock_search" value="1" {{ old('link_directly_to_stock_search', $manufacturer->link_directly_to_stock_search) == "1" ? "checked" : "" }}>
  </div>

  <div>
    <label for="logo">
      Logo
    </label>
    @if ($url = $manufacturer->getFirstMediaUrl('logo', 'thumb'))
      <img src="{{ $url }}">
    @endif
    <input name="logo" type="file" value="{{ old('logo', '') }}">
    @if ($errors->has('logo'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('logo') }}</p>
    @endif
  </div>

  <div>
    <label for="caravan_image">
      Caravan Image
    </label>
    @if ($url = $manufacturer->getFirstMediaUrl('caravanImage', 'thumb'))
      <img src="{{ $url }}">
    @endif
    <input name="caravan_image" type="file" value="{{ old('caravan_image', '') }}">
    @if ($errors->has('caravan_image'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('caravan_image') }}</p>
    @endif
  </div>

  <div>
    <label for="motorhome_image">
      Motorhome Image
    </label>
    @if ($url = $manufacturer->getFirstMediaUrl('motorhomeImage', 'thumb'))
      <img src="{{ $url }}">
    @endif
    <input name="motorhome_image" type="file" value="{{ old('motorhome_image', '') }}">
    @if ($errors->has('motorhome_image'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('motorhome_image') }}</p>
    @endif
  </div>

  <div>
    <label for="motorhome_intro_text">Motorhome Intro Text</label>
    <textarea name="motorhome_intro_text" id="motorhome_intro_text" cols="30" rows="10">{{ old('motorhome_intro_text', $manufacturer->motorhome_intro_text) }}</textarea>
  </div>

  <div>
    <label for="name">
      Motorhome Position
    </label>
    <input name="motorhome_position" type="number" value="{{ old('motorhome_position', $manufacturer->motorhome_position) }}">
    @if ($errors->has('motorhome_position'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('motorhome_position') }}</p>
    @endif
  </div>

  <div>
    <label for="caravan_intro_text">Caravan Intro Text</label>
    <textarea name="caravan_intro_text" id="caravan_intro_text" cols="30" rows="10">{{ old('caravan_intro_text', $manufacturer->caravan_intro_text) }}</textarea>
  </div>

  <div>
    <label for="name">
      Caravan Position
    </label>
    <input name="caravan_position" type="number" value="{{ old('caravan_position', $manufacturer->caravan_position) }}">
    @if ($errors->has('caravan_position'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('caravan_position') }}</p>
    @endif
  </div>

  @include('admin._partials.site-checkboxes', [
    'sites' => $sites,
    'objectSiteIds' => $manufacturer->sites()->pluck('id')->toArray(),
  ])

  <fieldset class="border pt-5 pl-5 mb-3 bg-gray-100">
    <legend><b>Linked Manufacturers</b></legend>
    @foreach ($manufacturers as $linkedManufacturer)
      <div class="mb-2">
        <label class="inline-flex items-center">
          <input type="checkbox" name="linked_manufacturer_ids[]" value="{{$linkedManufacturer->id}}" {{ in_array($linkedManufacturer->id, old('linked_manufacturer_ids', $linkedManufacturerIds))  ? ' checked' : '' }}>
          <span>{{$linkedManufacturer->name}}</span>
        </label>
      </div>
    @endforeach
  </fieldset>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($manufacturer->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>