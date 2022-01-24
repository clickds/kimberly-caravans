<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($asset->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $asset->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="file">File</label>
    <input type="file" name="file" id="file">
  </div>

  <div>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($asset->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>