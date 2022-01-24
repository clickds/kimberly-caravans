<fieldset class="mb-6 border p-3">
  <legend class="text-xl mb-2">Link stock items when importing from the feed</legend>
  <div class="-mx-2 flex flex-wrap mb-5">
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_used_caravan_stock" value="0">
      <input id="link_used_caravan_stock" name="link_used_caravan_stock" type="checkbox" value="1"{{ old('link_used_caravan_stock', $specialOffer->link_used_caravan_stock) ? ' checked' : '' }}>
      <label for="link_used_caravan_stock">Link Used Caravan Stock <span class="text-xs">(links all used caravan stock items)</span></label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_used_motorhome_stock" value="0">
      <input id="link_used_motorhome_stock" name="link_used_motorhome_stock" type="checkbox" value="1"{{ old('link_used_motorhome_stock', $specialOffer->link_used_motorhome_stock) ? ' checked' : '' }}>
      <label for="link_used_motorhome_stock">Link Used Motorhome Stock <span class="text-xs">(links all used motorhome stock items)</span></label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_managers_special_stock" value="0">
      <input id="link_managers_special_stock" name="link_managers_special_stock" type="checkbox" value="1"{{ old('link_managers_special_stock', $specialOffer->link_managers_special_stock) ? ' checked' : '' }}>
      <label for="link_managers_special_stock">Link Managers Special Stock <span class="text-xs">(links all stock items where managers special - caravans and motorhomes)</span></label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_on_sale_stock" value="0">
      <input id="link_on_sale_stock" name="link_on_sale_stock" type="checkbox" value="1"{{ old('link_on_sale_stock', $specialOffer->link_on_sale_stock) ? ' checked' : '' }}>
      <label for="link_on_sale_stock">Link On Sale Stock (Reduced Price) <span class="text-xs">(links all stock items where price has been reduced - caravans and motorhomes)</span></label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_on_sale_stock" value="0">
      <input id="link_on_sale_stock" name="link_on_sale_stock" type="checkbox" value="1"{{ old('link_on_sale_stock', $specialOffer->link_on_sale_stock) ? ' checked' : '' }}>
      <label for="link_on_sale_stock">Link On Sale Stock (Reduced Price) <span class="text-xs">(links all stock items where price has been reduced - caravans and motorhomes)</span></label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_feed_special_offers_caravans" value="0">
      <input id="link_feed_special_offers_caravans" name="link_feed_special_offers_caravans" type="checkbox" value="1"{{ old('link_feed_special_offers_caravans', $specialOffer->link_feed_special_offers_caravans) ? ' checked' : '' }}>
      <label for="link_feed_special_offers_caravans">Link to SCS Special offers stock caravans</label>
    </div>
    <div class="px-2 w-full flex flex-row items-center space-x-3">
      <input type="hidden" name="link_feed_special_offers_motorhomes" value="0">
      <input id="link_feed_special_offers_motorhomes" name="link_feed_special_offers_motorhomes" type="checkbox" value="1"{{ old('link_feed_special_offers_motorhomes', $specialOffer->link_feed_special_offers_motorhomes) ? ' checked' : '' }}>
      <label for="link_feed_special_offers_motorhomes">Link to SCS Special offers stock motorhomes</label>
    </div>
  </div>
</fieldset>

<div class="my-10 text-lg">Alternatively, you can select individual stock items below. If you have selected any of the options above, changing these will have no effect.</div>

<feed-stock-item-selection
  field-name="feed_caravan_stock_item_ids[]"
  legend="Link individual caravan stock items from the feed"
  :current-stock-items="{{ json_encode($currentCaravanFeedStockItems) }}"
  search-url="{{ route('api.admin.feed-stock-items.caravan-search') }}">
</feed-stock-item-selection>

<feed-stock-item-selection
  field-name="feed_motorhome_stock_item_ids[]"
  legend="Link individual motorhome stock items from the feed"
  :current-stock-items="{{ json_encode($currentMotorhomeFeedStockItems) }}"
  search-url="{{ route('api.admin.feed-stock-items.motorhome-search') }}">
</feed-stock-item-selection>