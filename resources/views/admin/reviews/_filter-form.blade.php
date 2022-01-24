<section class="mb-4">
  <h3>Filter</h3>

  <form action="" class="admin-form" method="get">
    <div class="flex flex-wrap -mx-2">
      <div class="w-full md:w-1/2 px-2">
        <label for="title_cont">Title</label>
        <input type="text" name="title_cont" value="{{ request()->input('title_cont') }}">
      </div>

      <div class="w-full md:w-1/2 px-2">
        <label for="review_category_id_eq">Category</label>
        <select name="review_category_id_eq">
          <option value="">Any</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"{{ request()->input('review_category_id_eq') == $category->id ? ' selected' : ''}}>
              {{ $category->name }}
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