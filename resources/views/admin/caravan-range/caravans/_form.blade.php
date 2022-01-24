<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($caravan->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div class="flex flex-wrap -mx-2">
    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="name">
        Name
      </label>
      <input name="name" value="{{ old("name", $caravan->name) }}" type="text" placeholder="Name" required>
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="position">
        Position
      </label>
      <input name="position" value="{{ old("position", $caravan->position) }}" type="number" step="1" placeholder="Position" required>
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="axles">
        Axles
      </label>
      <select name="axles">
        @foreach($axles as $axle)
          <option value="{{ $axle }}"{{ old('axles', $caravan->axles) == $axle ? ' selected' : '' }}>
            {{ $axle }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="layout_id">
        Layout
      </label>
      <select name="layout_id">
        @foreach($layouts as $layout)
          <option value="{{ $layout->id }}"{{ old('layout_id', $caravan->layout_id) == $layout->id ? ' selected' : '' }}>
            {{ $layout->nameWithCode() }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-2 px-2 w-full md:w-1/3">
      <label for="length">
        Length (m)
      </label>
      <input type="number" name="length" value="{{ old('length', $caravan->length) }}" step="0.01">
    </div>

    <div class="mb-2 px-2 w-full md:w-1/3">
      <label for="width">
        Width (m)
      </label>
      <input type="number" name="width" value="{{ old('width', $caravan->width) }}" step="0.01">
    </div>

    <div class="mb-2 px-2 w-full md:w-1/3">
      <label for="height">
        Height (m)
      </label>
      <input type="number" name="height" value="{{ old('height', $caravan->height) }}" step="0.01">
    </div>

    <div class="my-2 px-2 w-full md:w-1/2 flex items-center">
      <label class="inline-flex items-center">
        <input type="hidden" name="height_includes_aerial" value="0">
        <input name="height_includes_aerial" value="1" type="checkbox"{{ old('height_includes_aerial', $caravan->height_includes_aerial) ? ' checked' : ''}}>
        <span class="ml-2 text-shiraz">Height includes aerial</span>
      </label>
    </div>

    <div class="my-2 px-2 w-full md:w-1/2 flex items-center">
      <label class="inline-flex items-center">
        <input type="hidden" name="exclusive" value="0">
        <input name="exclusive" value="1" type="checkbox"{{ old('exclusive', $caravan->exclusive) ? ' checked' : ''}}>
        <span class="ml-2 text-shiraz">Exclusive</span>
      </label>
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="mro">MRO (Unladen Weight in Kg)</label>
      <input type="number" name="mro" value="{{ old('mro', $caravan->mro) }}" step="1">
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="mtplm">MTPLM (Max Weight in Kg)</label>
      <input type="number" name="mtplm" value="{{ old('mtplm', $caravan->mtplm) }}" step="1">
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="payload">Payload</label>
      <input type="number" name="payload" value="{{ old('payload', $caravan->payload) }}" step="1">
    </div>

    <div class="mb-2 px-2 w-full md:w-1/2">
      <label for="year">Year</label>
      <input type="text" name="year" value="{{ old('year', $caravan->year) }}" required>
    </div>
  </div>

  <div class="mb-2">
    <label for="description">
      Description
    </label>
    <textarea rows="3" name="description">{{ old('description', $caravan->description) }}</textarea>
  </div>

  <div class="mb-2">
    <label for="small_print">
      Small Print
    </label>
    <textarea rows="3" name="small_print">{{ old('small_print', $caravan->small_print) }}</textarea>
  </div>

  <div class="mb-2">
    <label for="video_id">
      Video (for stock item)
    </label>
    <select name="video_id">
      <option value="">None</option>
      @foreach($videos as $video)
        <option value="{{ $video->id }}"{{ old('video_id', $caravan->video_id) == $video->id ? ' selected' : '' }}>
          {{ $video->title }}
        </option>
      @endforeach
    </select>
  </div>


  <div class="grid grid-cols-3 gap-5">
    <div>
      <div>
        <p class="mb-2 font-bold text-sm" style="color:#4a5568;">Day Floorplan</p>
        @if ($caravan->hasMedia('dayFloorplan'))
          <div>
            <div class="relative shadow border rounded p-3">
            <div>
              <img src="{{ $caravan->getFirstMediaUrl('dayFloorplan', 'thumb') }}">
            </div>
            </div>
          </div>
        @endif
        <div>
          <input name="day_floorplan" type="file" value="{{ old('day_floorplan', '') }}">
          @if ($errors->has('day_floorplan'))
            <p class="text-red-500 text-xs italic">{{ $errors->first('day_floorplan') }}</p>
          @endif
        </div>
      </div>
    </div>

    <div>
      <div>
        <p class="mb-2 font-bold text-sm" style="color:#4a5568;">Night Floorplan</p>
        @if ($caravan->hasMedia('nightFloorplan'))
        <div>
            <div class="relative shadow border rounded p-3">
            <div class="flex flex-row">
              <img src="{{ $caravan->getFirstMediaUrl('nightFloorplan', 'thumb') }}">
              @if ($caravan->hasMedia('nightFloorplan'))
                <div>
                  <a onclick="document.getElementById('delete_image').submit()" class="bg-red-500 hover:bg-red-700 cursor-pointer text-white font-bold py-2 px-4 rounded inline-flex items-center absolute top-0 right-0">X</a>
                </div>
              @endif
            </div>
          </div>
        </div>
        @endif
        <div>
          <input name="night_floorplan" type="file" value="{{ old('night_floorplan', '') }}">
          @if ($errors->has('night_floorplan'))
            <p class="text-red-500 text-xs italic">{{ $errors->first('night_floorplan') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  @include('admin._partials.berths', [
    'berths' => $berths,
    'object' => $caravan,
  ])

  @include('admin._partials.site-prices', [
    'sites' => $sites,
    'object' => $caravan,
  ])

  @include('admin._partials.vehicle-stock-item-images', [
    'rangeGalleryImages' => $rangeGalleryImages,
    'stockItemImageIds' => $stockItemImageIds,
  ])

  <div>
    <input type="hidden" name="live" value="0">
    <label>
      <input type="checkbox" name="live" value="1"{{ $caravan->live ? ' checked' : ''}}> Live
    </label>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($caravan->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>

@if ($caravan->hasMedia('nightFloorplan'))
  <form method="POST" id="delete_image" action="{{ route('admin.caravans.night-floorplan.destroy', ['caravan' => $caravan]) }}">
      @method('DELETE')
      @csrf
  </form>
@endif