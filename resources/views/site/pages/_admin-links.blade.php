@auth
<div class="admin-links container mx-auto my-2">
  <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
    Admin Dashboard
  </a>
  <a href="{{ $pageFacade->adminEditUrl() }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
    @lang('global.edit-page')
  </a>
  <a href="{{ $pageFacade->adminAreasUrl() }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
    Edit Areas
  </a>
  @if ($editUrl = $pageFacade->editPageableUrl())
    <a href="{{ $editUrl }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
      Edit {{ $pageFacade->pageableName() }}
    </a>
  @endif
  @if ($viewAllLinkInfo = $pageFacade->adminViewAllLinkInfo())
    <a href="{{ $viewAllLinkInfo['url'] }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
      {{ $viewAllLinkInfo['name'] }}
    </a>
  @endif
</div>
@endauth