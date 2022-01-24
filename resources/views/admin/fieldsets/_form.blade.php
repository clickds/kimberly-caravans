<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($fieldset->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $fieldset->name) }}" type="text" placeholder="Name" required>
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Content" name="content"
    csrf-token="{{ csrf_token() }}"
    initial-value="{{ old('content', $fieldset->content) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($fieldset->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>