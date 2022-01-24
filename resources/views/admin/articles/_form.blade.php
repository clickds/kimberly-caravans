<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($article->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <tabs :navigation='{{ json_encode([
    [
      "title" => "Main Content",
      "slotName" => "main",
    ],
    [
      "title" => "Ranges",
      "slotName" => "ranges",
    ],
    [
      "title" => "Sites",
      "slotName" => "sites",
    ],
  ]) }}'>
    <template #main>
      @include("admin.articles.form-tabs.main", [
        'article' => $article,
        'articleCategories' => $articleCategories,
        'styles' => $styles,
        'types' => $types,
        'dealers' => $dealers,
      ])
    </template>
    <template #ranges>
      @include("admin.articles.form-tabs.ranges", [
        'caravanRanges' => $caravanRanges,
        'caravanRangeIds' => $caravanRangeIds,
        'motorhomeRanges' => $motorhomeRanges,
        'motorhomeRangeIds' => $motorhomeRangeIds,
      ])
    </template>
    <template #sites>
      @include("admin.articles.form-tabs.sites", [
        'sites' => $sites,
        'objectSiteIds' => $article->sites()->pluck('id')->toArray(),
      ])
    </template>
  </tabs>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($article->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
