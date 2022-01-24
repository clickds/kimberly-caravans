<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include("admin._partials.errors")

  @if ($specialOffer->exists)
    @method("put")
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <tabs :navigation='{{ json_encode([
    [
      "title" => "Main Content",
      "slotName" => "main",
    ],
    [
      "title" => "Sites",
      "slotName" => "sites",
    ],
    [
      "title" => "Virtual Stock",
      "slotName" => "virtual",
    ],
    [
      "title" => "Feed Stock",
      "slotName" => "feed",
    ]
  ]) }}'>
    <template #main>
      @include("admin.special-offers.form-tabs.main", [
        "specialOffer" => $specialOffer,
        "icons" => $icons,
      ])
    </template>
    <template #sites>
      @include("admin.special-offers.form-tabs.sites", [
        "currentSiteIds" => $currentSiteIds,
        "sites" => $sites,
      ])
    </template>
    <template #virtual>
      @include("admin.special-offers.form-tabs.virtual-stock", [
        "currentCaravanIds" => $currentCaravanIds,
        "currentMotorhomeIds" => $currentMotorhomeIds,
        "manufacturers" => $manufacturers,
      ])
    </template>
    <template #feed>
      @include("admin.special-offers.form-tabs.feed-stock", [
        "specialOffer" => $specialOffer,
        "currentCaravanFeedStockItems" => $currentCaravanFeedStockItems,
        "currentMotorhomeFeedStockItems" => $currentMotorhomeFeedStockItems,
      ])
    </template>
  </tabs>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($specialOffer->exists)
        @lang("global.update")
      @else
        @lang("global.create")
      @endif
    </button>
  </div>
</form>