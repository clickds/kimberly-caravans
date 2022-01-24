<div class="manufacturer-tabs">
  @if($pageFacade->getSite()->display_exclusive_manufacturers_separately)
    <tabs :show-secondary-navigation="false" :raised="true" :tabs='{{ json_encode($tabNames) }}'>
      <template #all>
        <div class="container mx-auto px-standard">
          <div class="w-full my-5 pt-5 flex flex-wrap -mx-2">
            @foreach ($allManufacturerPages as $page)
              @include('site.shared.new-vehicle-tab-content', [
                'page' => $page,
              ])
            @endforeach
          </div>
        </div>
      </template>

      @if($exclusivePages->isNotEmpty())
        <template #exclusive>
          <div class="container mx-auto px-standard">
            <div class="w-full my-5 pt-5 flex flex-wrap -mx-2">
              @foreach ($exclusivePages as $page)
                @include('site.shared.new-vehicle-tab-content', [
                  'page' => $page,
                ])
              @endforeach
            </div>
          </div>
        </template>
      @endif

      @if($otherManufacturerPages->isNotEmpty())
        <template #other>
          <div class="container mx-auto px-standard">
            <div class="w-full my-5 pt-5 flex flex-wrap -mx-2">
              @foreach ($otherManufacturerPages as $page)
                @include('site.shared.new-vehicle-tab-content', [
                  'page' => $page,
                ])
              @endforeach
            </div>
          </div>
        </template>
      @endif
    </tabs>
  @else
    <div class="container mx-auto px-standard">
      <div class="w-full my-5 pt-5 flex flex-wrap -mx-2">
        @foreach ($allManufacturerPages as $page)
          @include('site.shared.new-vehicle-tab-content', [
            'page' => $page,
          ])
        @endforeach
      </div>
    </div>
  @endif
</div>