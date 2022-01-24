<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($optionalWeight->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="name">Name</label>
    <input type="text" name="name" value="{{ old('name', $optionalWeight->name) }}">
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Value"
    name="value"
    initial-value="{{ old('value', $optionalWeight->value) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($optionalWeight->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>