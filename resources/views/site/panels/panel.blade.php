<div class="{{ $panel->cssClasses() }}">
  @auth
  <div class="admin-links p-4 mx-auto absolute top-0 left-0 z-50">
    <a href="{{ route('admin.areas.panels.edit', [
      'page' => $page,
      'area' => $area,
      'panel' => $panel,
      'redirect_url' => $page->link(),
    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
      @lang('panels.edit')
    </a>
  </div>
  @endauth

  @include($panel->partialPath(), [
    'area' => $area,
    'page' => $page,
    'panel' => $panel,
  ])
</div>