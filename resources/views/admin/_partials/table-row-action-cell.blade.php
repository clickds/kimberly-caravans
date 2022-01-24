<div class="w-full flex action justify-center">
  @if(isset($additional))
    @foreach($additional as $link)
      <div>
        <a href="{{ $link['url'] }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full inline-block ml-1">
          {{ $link['text'] }}
        </a>
      </div>
    @endforeach
  @endif

  @if (isset($show))
    <div><a href="{{ $show }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full inline-block ml-1">Show</a></div>
  @endif

  @if (isset($edit))
    <div><a href="{{ $edit }}" class="bg-green-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full inline-block ml-1">Edit</a></div>
  @endif

  @if (isset($destroy))
    <div>
      <form action="{{ $destroy }}" method="POST">
        @method('delete')
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-full inline-block ml-1">
          Delete
        </button>
      </form>
    </div>
  @endif
</div>