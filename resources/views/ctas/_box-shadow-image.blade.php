<div class="flex-grow-0">
  <a href="{{ $page->link() }}">
    @if ($image = $cta->getImage())
    <div class="image-object-fill image-object-center image-box-shadow relative mb-4">
      <h3 class="absolute inset-x-0 top-0 text-white font-medium font-heading px-4 py-2 text-lg leading-tight md:text-h3">
        {{ $cta->title }}
      </h3>
      {{ $image('responsive-box-shadow') }}
    </div>
    @else
      <h3 class="text-endeavour font-medium font-heading p-2 text-base text-lg md:text-h3">
        {{ $cta->title }}
      </h3>
    @endif
  </a>
</div>