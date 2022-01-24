<section class="mb-4">
  <h3>Filter</h3>

  <form action="" class="admin-form" method="get">
    <div class="flex flex-wrap -mx-2">
      <div class="w-full md:w-1/2 px-2">
        <label for="title_cont">Title</label>
        <input type="text" name="title_cont" value="{{ request()->input('title_cont') }}">
      </div>
      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="sort_by">Sort By</label>
        <select name="sort_by">
          @foreach([
            'updated_at_desc' => 'Updated At Desc',
            'updated_at_asc' => 'Updated At Asc',
            'title_desc' => 'Title Desc',
            'title_asc' => 'Title Asc',
          ] as $value => $name)
            <option value="{{ $value }}"{{ request()->input('sort_by', 'updated_at_desc') == $value ? ' selected' : ''}}>
              {{ $name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div>
      <button
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded"
        type="submit"
      >
        Filter
      </button>
    </div>
  </form>
</section>