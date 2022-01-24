<div class="grid grid-cols-6 gap-3">
  @foreach ($stockItem->photos() as $photo)
    <div>
      {{ $photo('responsiveStockSlider') }}
    </div>
  @endforeach
  @if ($stockItem->hasFeedSource())
    @foreach($stockItem->floorplans() as $floorplan)
      <div>
        <img src="{{ $floorplan->getFullUrl() }}" alt="{{ $floorplan->name }}">
      </div>
    @endforeach
  @else
    @foreach($stockItem->floorplans() as $floorplan)
      <div>
        {{ $floorplan('show') }}
      </div>
    @endforeach
  @endif
</div>
