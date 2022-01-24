<div class="container mx-auto px-standard flex flex-wrap">
  <div class="w-full lg:w-3/4 mb-4 lg:mb-0">
    @include('site.shared.pagination.core-pagination', [
      'paginator' => $paginator,
    ])
  </div>
  <div class="w-full lg:w-1/4 text-left lg:text-right font-heading font-semibold">
    Show: @foreach([12, 24, 48] as $perPageOption)
      @if ($paginator->perPage() == $perPageOption)
        <span class="ml-1 text-shiraz">{{ $perPageOption }}</span>
      @else
        <a href="{{ $paginator->withQueryString()->appends(['per_page' => $perPageOption])->url($paginator->currentPage()) }}"
          class="ml-1 text-endeavour underline">{{ $perPageOption }}</a>
      @endif
    @endforeach
  </div>
</div>