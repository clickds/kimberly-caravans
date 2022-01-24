@foreach ($brochureGroups as $brochureGroup)
<div class="slide-toggle {{ $loop->last ? '' : 'mb-4' }}">
  <div class="bg-endeavour text-white py-2 px-4 flex items-center">
    <h2 class="flex-grow text-white font-semibold">
      {{ $brochureGroup->name }}
    </h2>
    <div class="text-xl">
      <button class="flex items-center" data-toggle="open">
        <i class="fas fa-plus-circle"></i>
      </button>
      <button class="hidden" data-toggle="close">
        <i class="fas fa-minus-circle"></i>
      </button>
    </div>
  </div>
  <div class="toggle-content" data-toggle="content">
    <div class="bg-gallery p-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($brochureGroup->brochures as $brochure)
          @include('site.shared.brochure', [
            'brochure' => $brochure,
          ])
        @endforeach
      </div>
    </div>
  </div>
</div>
@endforeach