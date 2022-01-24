<div class="mb-6">
  <label for="name">
    Name
  </label>
  <input name="name" value="{{ old("name", $specialOffer->name) }}" type="text" placeholder="Name" required>
  <div class="text-xs">Used on the stock item bars unless Link On Sale Stock selected</div>
</div>

<div class="mb-6">
  <label for="type">
    Type
  </label>
  <select name="type">
    @foreach($types as $type)
      <option value="{{ $type }}"{{ old('type', $specialOffer->type) == $type ? ' selected' : '' }}>
        {{ $type }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-6">
  <label for="offer_type">
    Offer Type
  </label>
  <select name="offer_type">
    @foreach($offerTypes as $type)
      <option value="{{ $type }}"{{ old('offer_type', $specialOffer->offer_type) == $type ? ' selected' : '' }}>
        {{ $type }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-6">
  <label for="landscape_image">
    Landscape Image
  </label>
  <input name="landscape_image" type="file" value="{{ old('landscape_image', '') }}">
</div>

<div class="mb-6">
  <label for="square_image">
    Square Image
  </label>
  <input name="square_image" type="file" value="{{ old('square_image', '') }}">
</div>

<div class="mb-6">
  <label for="url">Url</label>
  <input name="url" type="text" value="{{ old('url', $specialOffer->url) }}">
  <p>Url to link to on new products page if no document attached</p>
</div>

<div class="mb-6">
  <label for="document">Document</label>
  <input name="document" type="file" value="{{ old('document', '') }}">
  <p>Will be linked to on new products page</p>
</div>

<wysiwyg-field
  label="Content"
  name="content"
  csrf-token="{{ csrf_token() }}"
  initial-value="{{ old('content', $specialOffer->content) }}"
  assetsPageUrl="{{ route('admin.assets.index') }}"
></wysiwyg-field>

<div class="mb-6">
  <label for="published_at">
    Published At
  </label>
  <input name="published_at" type="date" value="{{ old('published_at', $specialOffer->published_at) }}" placeholder="Published At">
</div>

<div class="mb-6">
  <label for="expired_at">
    Expired At
  </label>
  <input name="expired_at" type="date" value="{{ old('expired_at', $specialOffer->expired_at) }}" placeholder="Expired At">
</div>

<div class="mb-6">
  @include('admin._partials.colour-input', [
    'label' => 'Stock Bar Colour',
    'name' => 'stock_bar_colour',
    'colours' => $stockBarColours,
    'value' => old('stock_bar_colour', $specialOffer->stock_bar_colour)
  ])
</div>

<div class="mb-6">
  @include('admin._partials.colour-input', [
    'label' => 'Stock Bar Text Colour',
    'name' => 'stock_bar_text_colour',
    'colours' => $stockBarTextColours,
    'value' => old('stock_bar_text_colour', $specialOffer->stock_bar_text_colour)
  ])
</div>

<fieldset>
  <legend>Icon</legend>
  <div class="grid grid-cols-2 md:grid-cols-5">
    @foreach ($icons as $icon)
      <div class="w-20 mb-2">
        <label>
          <div class="flex">
            <input type="radio" name="icon" value="{{ $icon }}"{{ old('icon', $specialOffer->icon) == $icon ? ' checked' : '' }}>
            <div>
              <img src="{{ \App\Models\SpecialOffer::iconDirectoryUrl() }}/{{ $icon }}" class="ml-2 border border-black"/>
              <div>{{ $icon }}</div>
            </div>
          </div>
        </label>
      </div>
    @endforeach
  </div>
</fieldset>

<div>
  <label for="position">Position</label>
  <input id="position" name="position" type="number" value="{{ old('position', $specialOffer->position) }}" step="1" min="0">
</div>

<div>
  <input type="hidden" name="live" value="0">
  <label for="live">Live</label>
  <input id="live" name="live" type="checkbox" value="1"{{ old('live', $specialOffer->live) ? ' checked' : '' }}>
</div>