@if ($page->exists)
  <a href="{{ $page->link() }}" class="text-endeavour hover:text-shiraz underline mt-2 flex-grow-0">
    {{ $linkText }}
  </a>
@endif