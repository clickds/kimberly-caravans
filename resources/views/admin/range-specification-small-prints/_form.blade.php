<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @csrf
  @include('admin._partials.errors')
  @if($rangeSpecificationSmallPrint->exists)
    @method('PUT')
  @endif

  <div>
    <label for="site_id">Site</label>
    <select name="site_id">
      @foreach($sites as $site)
        <option value="{{ $site->id }}"{{ old('site_id', $rangeSpecificationSmallPrint->site_id) == $site->id ? ' selected' : '' }}>
          {{ $site->country }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $rangeSpecificationSmallPrint->name) }}" type="text" placeholder="Name" required>
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Content"
    name="content"
    initial-value="{{ old('content', $rangeSpecificationSmallPrint->content) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $rangeSpecificationSmallPrint->position) }}" type="number" step="1" placeholder="Position">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($rangeSpecificationSmallPrint->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>