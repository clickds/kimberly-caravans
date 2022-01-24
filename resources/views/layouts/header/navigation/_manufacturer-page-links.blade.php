@if($pages->isNotEmpty())
<h4 class="text-web-orange text-lg">{{ $title }}</h4>

<ul class="nav-column">
  @foreach ($pages as $page)
    <li>
      <a href="{{ $page->link() }}">
        {{ $page->linkTitle() }}
      </a>
    </li>
  @endforeach
</ul>
@endif