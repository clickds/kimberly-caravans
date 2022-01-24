<div class="slide-toggle mb-3">
  <div class="bg-endeavour text-white py-2 px-4 flex items-center">
    <button class="w-full flex items-center space-between" data-toggle="open">
      <h2 class="flex-grow text-white text-h4 md:text-h2">
        {{ $feature->name }}
      </h2>
      <i class="fas fa-plus-circle"></i>
    </button>
    <button class="hidden w-full flex items-center space-between" data-toggle="close">
      <h2 class="flex-grow text-white text-h4 md:text-h2">
        {{ $feature->name }}
      </h2>
      <i class="fas fa-minus-circle"></i>
    </button>
  </div>
  <div class="toggle-content" data-toggle="content">
    <div class="wysiwyg bg-gallery p-4">
      {!! $feature->content !!}
    </div>
  </div>
</div>