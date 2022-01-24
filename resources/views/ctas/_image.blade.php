<div>
  @if ($image = $cta->getImage())
  <div class="image-object-fill image-object-center relative mb-4">
    <h3 class="absolute inset-x-0 top-0 text-white font-medium font-heading px-4 py-2">
      {{ $cta->title }}
    </h3>
    {{ $image('responsive-box-shadow') }}
  </div>
  @else
    <h3 class="text-endeavour font-medium font-heading p-2">
      {{ $cta->title }}
    </h3>
  @endif
</div>