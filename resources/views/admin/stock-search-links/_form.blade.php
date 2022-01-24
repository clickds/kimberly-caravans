<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include("admin._partials.errors")

  @if ($stockSearchLink->exists)
    @method("put")
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $stockSearchLink->name) }}">
  </div>

  <div>
    <label for="type">Type</label>
    <select name="type" id="type">
      @foreach ($types as $type)
        <option value="{{ $type }}"{{ old('type', $stockSearchLink->type) == $type ? ' selected': ''}}>
          {{ $type }}
        </option>
      @endforeach
    </select>
  </div>

  <site-page-fields :sites='@json($sites)'
    page-field-name="page_id"
    page-field-label="Page To Link To"
    :initial-site-id='@json(old('site_id', $stockSearchLink->site_id))'
    :initial-page-id='@json(old('page_id', $stockSearchLink->page_id))'>
  </site-page-fields>

  <div>
    @if ($image = $stockSearchLink->getFirstMedia('image'))
      <img src="{{ $image->getUrl('thumb') }}" alt="{{ $image->name }}">
    @endif
    <label for="image">Image</label>
    <input type="file" name="image">
  </div>

  <div>
    @if ($mobileImage = $stockSearchLink->getFirstMedia('mobile-image'))
      <img src="{{ $mobileImage->getUrl('thumb') }}" alt="{{ $mobileImage->name }}">
    @endif
    <label for="image">Mobile Image</label>
    <input type="file" name="mobile_image">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($stockSearchLink->exists)
        @lang("global.update")
      @else
        @lang("global.create")
      @endif
    </button>
  </div>
</form>