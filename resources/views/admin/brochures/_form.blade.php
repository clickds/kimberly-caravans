<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($brochure->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="site_id">
      Site
    </label>
    <select name="site_id" required>
      @foreach($sites as $site)
        <option value="{{ $site->id }}"{{ old('site_id', $brochure->site_id) == $site->id ? ' selected': '' }}>
          {{ $site->country }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="group_id">
      Brochure Group
    </label>
    <select name="group_id" required class="border rounded shadow px-3">
      <option>--</option>
      @foreach($brochureGroups as $brochureGroup)
        <option value="{{ $brochureGroup->id }}"{{ old('group_id', $brochure->group_id) == $brochureGroup->id ? ' selected': '' }}>
          {{ $brochureGroup->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old('title', $brochure->title) }}" type="text" placeholder="Title" required>
  </div>

  @if ($brochure->hasMedia('image'))
    <p class="mb-1 font-bold" style="color:#4a5568;">Image</p>
    <div class="relative shadow border rounded p-3">
    <div>
      <img src="{{ $brochure->getFirstMediaUrl('image') }}">
    </div>
    <div>
      <a onclick="document.getElementById('delete_image').submit()" class="bg-red-500 hover:bg-red-700 cursor-pointer text-white font-bold py-2 px-4 rounded inline-flex items-center absolute top-0 right-0">X
      </a>
    </div>
    </div>
  @else
    <div>
      <label for="image">
        Image
      </label>
      <input name="image" type="file" value="{{ old('image', '') }}" required>
    </div>
    @if ($errors->has('image'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('image') }}</p>
    @endif
  @endif

  @if ($brochure->hasMedia('brochure_file'))
    <p class="mb-1 font-bold" style="color:#4a5568;">Brochure File</p>
    <div class="relative shadow border rounded p-3">
      <div>
        <a href="{{ $brochure->getFirstMediaUrl('brochure_file') }}" target="_blank" rel="noopener" rel="noreferrer">{{ $brochure->getFirstMediaUrl('brochure_file') }}</a>
      </div>
      <div>
        <a onclick="document.getElementById('delete_brochure_file').submit()" class="bg-red-500 hover:bg-red-700 cursor-pointer text-white font-bold py-2 px-4 rounded inline-flex items-center absolute top-0 right-0">X
        </a>
      </div>
    </div>
  @else
    <div>
      <label for="brochure">
        Brochure File
      </label>
      <input name="brochure_file" type="file" value="{{ old('brochure_file', '') }}" required>
    </div>
    @if ($errors->has('brochure_file'))
      <p class="text-red-500 text-xs italic">{{ $errors->first('brochure_file') }}</p>
    @endif
  @endif

  <div>
    <label for="url">
      Url
    </label>
    <input name="url" value="{{ old('url', $brochure->url) }}" type="text" placeholder="Url">
  </div>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $brochure->published_at ? $brochure->published_at->format('Y-m-d') :'') }}">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $brochure->expired_at ? $brochure->expired_at->format('Y-m-d') : '' ) }}">
  </div>

  @include('admin._partials.caravan-and-motorhome-ranges-checkboxes', [
    'caravanRanges' => $caravanRanges,
    'caravanRangeIds' => $brochure->caravanRanges->pluck('id')->toArray(),
    'motorhomeRanges' => $motorhomeRanges,
    'motorhomeRangeIds' => $brochure->motorhomeRanges->pluck('id')->toArray(),
  ])

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($brochure->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>


@if ($brochure->hasMedia('image'))
    <form method="POST" id="delete_image" action="{{ route('admin.brochures.destroyImage', [$brochure->getFirstMedia('image')->id]) }}">
        @method('DELETE')
        @csrf
    </form>
@endif

@if ($brochure->hasMedia('brochure_file'))
    <form method="POST" id="delete_brochure_file" action="{{ route('admin.brochures.destroyImage', [$brochure->getFirstMedia('brochure_file')->id]) }}">
        @method('DELETE')
        @csrf
    </form>
@endif