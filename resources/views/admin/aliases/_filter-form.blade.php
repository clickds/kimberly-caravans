<section class="mb-4">
  <h3>Filter</h3>

  <form action="" class="admin-form" method="get">
    <fieldset>
      <legend>Sites</legend>

      <div class="flex flex-wrap -mx-2">
        @foreach ($sites as $site)
          <div class="w-full md:w-1/3 lg:w-1/5 mb-2 px-2">
            <label>
              <input type="checkbox" name="site_id_in[]"
                value="{{ $site->id }}"{{ in_array($site->id, request()->input('site_id_in', [])) ? ' checked' : ''}}>
              {{ $site->country }}
            </label>
          </div>
        @endforeach
      </div>
    </fieldset>

    <div class="flex flex-wrap -mx-2">
      <div class="w-full md:w-1/2 px-2">
        <label for="capture_path_cont">Capture Path</label>
        <input type="text" name="capture_path_cont" value="{{ request()->input('capture_path_cont') }}">
      </div>
    </div>

    <div>
      <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded"
        type="submit">Filter</button>
    </div>
  </form>
</section>