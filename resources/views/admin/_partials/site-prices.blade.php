<fieldset class="p-10 bg-gray-100 border">
  <legend>Sites</legend>
  <div class="mb-5">Enter the standard price (RRP) of the model in the recommended price field. Enter any reduced prices (reduced/sale prices) in the price fields.</div>
  <div class="grid grid-cols-4 gap-10">
    @foreach ($sites as $index => $site)
      @php
        $existingSite =  $object->sites->firstWhere('id', $site->id);
      @endphp
      <div>
        <div>
          <label>
            <input name="sites[{{ $index }}][id]" id="site_{{ $site->id }}_id" value="{{ $site->id }}"
              type="checkbox"{{ is_null($existingSite) ? '' : ' checked'}}>
            {{ $site->country }}
          </label>
        </div>
        <div>
          <label for="site_{{ $site->id }}_recommended_price">
            Recommended Price
          </label>
          @php
            $recommendedPriceValue = null;
            if ($existingSite) {
              $recommendedPriceValue = $existingSite->pivot->recommended_price;
            }
          @endphp
          <input id="site_{{ $site->id }}_recommended_price" name="sites[{{ $index }}][recommended_price]" type="number" value="{{ old("sites.{$index}.recommended_price", $recommendedPriceValue) }}">
        </div>
        <div>
          <label for="site_{{ $site->id }}_price">
            Price
          </label>
          @php
            $priceValue = null;
            if ($existingSite) {
              $priceValue = $existingSite->pivot->price;
            }
          @endphp
          <input id="site_{{ $site->id }}_price" name="sites[{{ $index }}][price]" type="number" value="{{ old("sites.{$index}.price", $priceValue) }}">
        </div>
      </div>
    @endforeach
  </div>
</fieldset>