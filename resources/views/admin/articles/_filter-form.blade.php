<section class="mb-4">
  <h3>Filter</h3>

  <form action="" class="admin-form" method="get">
    <div class="flex flex-wrap -mx-2">
      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="title_cont">Title</label>
        <input type="text" name="title_cont" value="{{ request()->input('title_cont') }}">
      </div>

      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="article_category_id">Category</label>
        <select name="article_category_id">
          <option value="">Any</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"{{ request()->input('article_category_id') == $category->id ? ' selected' : ''}}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="published_at_gte">Published At Min</label>
        <input type="date" name="published_at_gte" value="{{ request()->input('published_at_gte') }}">
      </div>

      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="published_at_lte">Published At Max</label>
        <input type="date" name="published_at_lte" value="{{ request()->input('published_at_lte') }}">
      </div>

      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="status">Status</label>
        <select name="status">
          @foreach([
            '' => 'Any',
            'draft' => 'Draft',
            'pending' => 'Pending',
            'published' => 'Published',
          ] as $value => $name)
            <option value="{{ $value }}"{{ request()->input('status', '') === $value ? ' selected' : ''}}>
              {{ $name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="w-full md:w-1/2 px-2 mb-2">
        <label for="sort_by">Sort By</label>
        <select name="sort_by">
          @foreach([
            'published_at_desc' => 'Published At Desc',
            'published_at_asc' => 'Published At Asc',
            'title_desc' => 'Title Desc',
            'title_asc' => 'Title Asc',
          ] as $value => $name)
            <option value="{{ $value }}"{{ request()->input('sort_by', 'published_at_desc') == $value ? ' selected' : ''}}>
              {{ $name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div>
      <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded"
        type="submit">Filter</button>
    </div>
  </form>
</section>