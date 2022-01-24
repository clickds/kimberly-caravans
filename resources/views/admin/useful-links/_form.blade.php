<form method="POST" action="{{ $url }}" class="admin-form" enctype="multipart/form-data">
  @include('admin._partials.errors')

  @if ($usefulLink->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div class="mb-6">
    <label for="useful_link_category_id">
      Category
    </label>
    <select name="useful_link_category_id" id="useful_link_category_id">
      @foreach ($usefulLinkCategories as $usefulLinkCategory)
        <option value="{{ $usefulLinkCategory->id }}"
          {{ old('useful_link_category_id', $usefulLink->useful_link_category_id) == $usefulLinkCategory->id ? 'selected' : ''}}
          >{{ $usefulLinkCategory->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-6">
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $usefulLink->name) }}" type="text" placeholder="Name" required>
  </div>

  <div class="mb-6">
    <label for="url">
      Url
    </label>
    <input name="url" value="{{ old("url", $usefulLink->url) }}" type="text" placeholder="Url" required>
  </div>

  <div class="mb-6">
    @if ($url = $usefulLink->getFirstMediaUrl('image', 'thumb'))
      <img src="{{ $url }}">
    @endif
    <label for="image">Image</label>
    <input type="file" name="image" id="image">
  </div>

  <div class="mb-6">
    <label for="position">
      Position
    </label>
    <input name="position" value="{{ old("position", $usefulLink->position) }}" type="number" placeholder="Position" required>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($usefulLink->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>