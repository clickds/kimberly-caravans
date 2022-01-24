<fieldset class="border pt-5 pl-5 mb-3 bg-gray-100">
  <legend class="font-bold">Caravans</legend>
    @foreach ($manufacturers as $manufacturer)
      @if ($manufacturer->caravanRanges->isNotEmpty())
        <fieldset class="border p-2">
          <legend>{{ $manufacturer->name }}</legend>
              <div class="flex">
                <a href="#" class="select-all-vehicles rounded p-2 bg-green-500 hover:bg-green-700 text-white mr-2 mb-2">Select All For Manufacturer</a>
                <a href="#" class="deselect-all-vehicles rounded p-2 bg-red-500 hover:bg-red-700 text-white mb-2">Deselect All For Manufacturer</a>
              </div>

          @foreach($manufacturer->caravanRanges as $caravanRange)
            <fieldset class="border p-2">
              <legend>{{ $caravanRange->name }}</legend>
              <div class="flex">
                <a href="#" class="select-all-vehicles rounded p-2 bg-green-500 hover:bg-green-700 text-white mr-2 mb-2">Select All For Range</a>
                <a href="#" class="deselect-all-vehicles rounded p-2 bg-red-500 hover:bg-red-700 text-white mb-2">Deselect All For Range</a>
              </div>

              <div class="-mx-2 flex flex-wrap">
                @foreach ($caravanRange->caravans as $caravan)
                  <div class="px-2 mb-2">
                    <label class="inline-flex items-center">
                      <input name="caravan_ids[]" value="{{ $caravan->id }}"
                        type="checkbox"{{ in_array($caravan->id, old('caravan_ids', $currentCaravanIds)) ? ' checked' : ''}}>
                      <span class="ml-2">{{ $caravan->name }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
            </fieldset>
          @endforeach
        </fieldset>
      @endif
    @endforeach
</fieldset>

  <fieldset class="border pt-5 pl-5 mb-3 bg-gray-100">
  <legend class="font-bold">Motorhomes</legend>
    @foreach ($manufacturers as $manufacturer)
      @if ($manufacturer->motorhomeRanges->isNotEmpty())
        <fieldset class="border p-2">
          <legend>{{ $manufacturer->name }}</legend>
              <div class="flex">
                <a href="#" class="select-all-vehicles rounded p-2 bg-green-500 hover:bg-green-700 text-white mr-2 mb-2">Select All For Manufacturer</a>
                <a href="#" class="deselect-all-vehicles rounded p-2 bg-red-500 hover:bg-red-700 text-white mb-2">Deselect All For Manufacturer</a>
              </div>

          @foreach($manufacturer->motorhomeRanges as $motorhomeRange)
            <fieldset class="border p-2">
              <legend>{{ $motorhomeRange->name }}</legend>
              <div class="flex">
                <a href="#" class="select-all-vehicles rounded bg-green-500 p-2 hover:bg-green-700 text-white mr-2 mb-2">Select All For Range</a>
                <a href="#" class="deselect-all-vehicles rounded bg-red-500 p-2 hover:bg-red-700 text-white mb-2">Deselect All For Range</a>
              </div>
              <div class="-mx-2 flex flex-wrap">
                @foreach ($motorhomeRange->motorhomes as $motorhome)
                  <div class="px-2 mb-2">
                    <label class="inline-flex items-center">
                      <input name="motorhome_ids[]" value="{{ $motorhome->id }}"
                        type="checkbox"{{ in_array($motorhome->id, old('motorhome_ids', $currentMotorhomeIds)) ? ' checked' : ''}}>
                      <span class="ml-2">{{ $motorhome->name }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
            </fieldset>
          @endforeach
        </fieldset>
      @endif
    @endforeach
</fieldset>
