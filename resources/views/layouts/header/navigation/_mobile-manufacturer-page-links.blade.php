@if($pages->isNotEmpty())
<div class="title">{{ $title }}</div>

<ul class="manufacturer-nav">
  @foreach ($pages as $page)
    <li>
      <a href="{{ $page->link() }}">
        - {{ $page->linkTitle() }}
      </a>
    </li>
  @endforeach
</ul>
@endif