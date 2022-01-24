<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @csrf
  @include('admin._partials.errors')
  @if($rangeFeature->exists)
    @method('PUT')
  @endif

  @include('admin._partials.redirect-input')

  @include('admin._partials.site-checkboxes', [
    'objectSiteIds' => $rangeFeature->sites()->pluck('id')->toArray(),
    'sites' => $sites,
  ])

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $rangeFeature->name) }}" type="text" placeholder="Name" required>
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Content"
    name="content"
    initial-value="{{ old('content', $rangeFeature->content) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div>
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $rangeFeature->position) }}" type="number" step="1" placeholder="Position">
  </div>

  <div class="flex items-center">
    <label class="inline-flex items-center">
      <input type="hidden" name="key" value="0">
      <input name="key" value="1" type="checkbox"{{ old('key', $rangeFeature->key) ? ' checked' : ''}}>
      <span class="ml-2 text-shiraz">Key Feature</span>
    </label>
  </div>

  <div class="flex items-center">
    <label class="inline-flex items-center">
      <input type="hidden" name="warranty" value="0">
      <input name="warranty" value="1" type="checkbox"{{ old('warranty', $rangeFeature->warranty) ? ' checked' : ''}}>
      <span class="ml-2 text-shiraz">Warranty Feature</span>
    </label>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($rangeFeature->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>