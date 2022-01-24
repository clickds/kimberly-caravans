<form method="POST" action="{{ $url }}" class="admin-form border">
  @include('admin._partials.errors')

  <input type="hidden" name="navigation-hierarchy" value="empty">

  @if ($navigation->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $navigation->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="site_id">
      Site
    </label>
    <select name="site_id" required class="shadow appearance-none border  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        @foreach($sites as $site)
          <option value="{{ $site->id }}"{{ old('site_id', $navigation->site_id) == $site->id ? ' selected': '' }}>
            {{ $site->country }}
            <small>{{ $site->is_default ? 'default' : '' }}</small>
          </option>
        @endforeach
    </select>
  </div>

  <div>
    <label for="type">
      Type
    </label>
    <select name="type" class="shadow appearance-none border  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="">Select type</option>
        @foreach($types as $value => $label)
          <option value="{{ $value }}"{{ old('type', $navigation->type) == $label ? ' selected': '' }}>
            {{ $label }}
          </option>
        @endforeach
    </select>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($navigation->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
