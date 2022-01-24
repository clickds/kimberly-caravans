@auth
  <div class="container mx-auto my-5">
    <div class="flex flex-wrap">
      <div class="w-full sm:w-3/4 mb-4">{{ $holder }}</div>
      <div class="w-full sm:w-1/4 sm:text-right">
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          href="{{ route('admin.pages.areas.create', ['page' => $page, 'holder' => $holder])}}">
          New Area
        </a>
      </div>
    </div>
  </div>
@endauth

<tabs :tabs='{{ json_encode($areas->pluck('name')->toArray()) }}'>
  @foreach ($areas as $area)
    <template #{{ \Illuminate\Support\Str::slug($area->name) }}>
      @include('site.areas.area', [
        'page' => $page,
        'area' => $area,
      ])
    </template>
  @endforeach
</tabs>
