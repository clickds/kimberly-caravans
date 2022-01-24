<div class="flex justify-between bg-gray-100 border p-10">
  <div class="w-5/12 bg-gray-200 p-8 border">
    <div class="mb-5">
      <span class="text-xl">Attached To Motorhome Ranges</span>
    </div>

    @foreach ($motorhomeRanges as $motorhomeRange)
      <label class="inline-flex items-center">
        <input type="checkbox" name="motorhome_range_ids[]" value="{{$motorhomeRange->id}}" {{ in_array(old('motorhome_range_ids', $motorhomeRange->id), $motorhomeRangeIds)  ? ' checked' : '' }}>
        <span>{{$motorhomeRange->name}}</span>
      </label>
    @endforeach
  </div>

  <div class="w-5/12 bg-gray-200 p-8 border">
    <div class="mb-5">
      <span class="text-xl">Attached To Caravan Ranges</span>
    </div>
    @foreach ($caravanRanges as $caravanRange)
      <label class="inline-flex items-center">
        <input type="checkbox" name="caravan_range_ids[]" value="{{$caravanRange->id}}" {{ in_array(old('caravan_range_ids', $caravanRange->id), $caravanRangeIds) ? ' checked' : '' }}>
        <span>{{$caravanRange->name}}</span>
      </label>
    @endforeach
  </div>
</div>