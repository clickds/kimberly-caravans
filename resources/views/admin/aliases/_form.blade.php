<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($alias->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="capture_path">
      Capture Path <span class="text-xs">Relative url e.g. /some-page</span>
    </label>
    <input name="capture_path" value="{{ old("capture_path", $alias->capture_path) }}" type="text" placeholder="Capture Path" required>
  </div>

  <site-page-fields :sites='@json($sites)'
    page-field-name="page_id"
    page-field-label="Page To Redirect To"
    :initial-site-id='@json(old('site_id', $alias->site_id))'
    :initial-page-id='@json(old('page_id', $alias->page_id))'>
  </site-page-fields>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($alias->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
