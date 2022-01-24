@auth
  <div class="admin-links container mx-auto my-2">
    <a href="{{ route('admin.pages.areas.edit', [
      'page' => $page,
      'area' => $area,
      'redirect_url' => url()->current(),
    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
      @lang('areas.edit', ['name' => $area->name])
    </a>
    <a href="{{ route('admin.areas.panels.index', [
      'area' => $area,
      'redirect_url' => route('site', [
        'page' => $page->slug,
      ]),
    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
      Edit Panels
    </a>
    <a href="{{ route('admin.areas.panels.create', [
      'area' => $area,
      'redirect_url' => url()->current(),
    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
      @lang('panels.create')
    </a>
  </div>
@endauth

<section class="{{ $area->cssClasses() }}">
  <div class="{{ $area->innerContainerCssClasses() }}">
    @if($area->getHeading())
      @include('site.areas._heading', [
        'area' => $area,
      ])
    @endif

    <div class="{{ $area->gridCssClasses() }}">
      @foreach($area->panels as $panel)
        @include('site.panels.panel', [
          'area' => $area,
          'panel' => $panel,
        ])
      @endforeach
    </div>
  </div>
</section>