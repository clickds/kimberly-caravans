<fieldset>
  <legend>Stock Item Images</legend>
  <div class="flex flex-wrap -mx-2 items-end">
    @foreach ($rangeGalleryImages as $rangeGalleryImage)
      <div class="px-2 mb-2 w-1/4">
        <label>
          <img src="{{ $rangeGalleryImage->getUrl('thumb') }}">
          <div>
            <input type="checkbox" name="stock_item_image_ids[]"
              value="{{ $rangeGalleryImage->id }}"
              {{ in_array($rangeGalleryImage->id, old('stock_item_image_ids', $stockItemImageIds)) ? ' checked' : '' }}>
            {{ $rangeGalleryImage->name }}
          </div>
        </label>
      </div>
    @endforeach
  </div>
</fieldset>