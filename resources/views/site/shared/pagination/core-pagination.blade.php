@if ($paginator->hasPages())
  <nav>
    <ul class="pagination">
      {{-- Previous Page Link --}}
      @if (!$paginator->onFirstPage())
        <li class="pagination-item first">
          <a href="{{ $paginator->url(1) }}" rel="first" aria-label="@lang('pagination.first')">
            <i class="fas fa-angle-double-left mr-1"></i>
            @lang('pagination.first')
          </a>
        </li>
        <li class="pagination-item previous">
          <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
            <i class="fas fa-angle-left mr-1"></i>
            @lang('pagination.previous')
          </a>
        </li>
      @endif

      @php
        if ($paginator->currentPage() <= 2) {
          $startPageNumber = 1;
          $endPageNumber = min(5, $paginator->lastPage());
        } else if ($paginator->currentPage() >= ($paginator->lastPage() -1)) {
          $endPageNumber = $paginator->lastPage();
          $startPageNumber = max(1, $endPageNumber - 5);
        } else {
          $startPageNumber = $paginator->currentPage() - 2;
          $endPageNumber = $paginator->currentPage() + 2;
        }
      @endphp
      @foreach(range($startPageNumber, $endPageNumber) as $pageNumber)
        @if ($paginator->currentPage() == $pageNumber)
          <li class="pagination-item active">
            <span>
              {{ $pageNumber }}
            </span>
          </li>
        @else
          <li class="pagination-item">
            <a href="{{ $paginator->url($pageNumber) }}">
              {{ $pageNumber }}
            </a>
          </li>
        @endif
      @endforeach
      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="pagination-item next">
          <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
            @lang('pagination.next')
            <i class="fas fa-angle-right ml-1"></i>
          </a>
        </li>
        <li class="pagination-item last">
          <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="last" aria-label="@lang('pagination.last')">
            @lang('pagination.last')
            <i class="fas fa-angle-double-right ml-1"></i>
          </a>
        </li>
      @endif
    </ul>
  </nav>
@endif