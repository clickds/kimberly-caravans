<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($bedSize->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="bed_description_id">
      Bed Description
    </label>
    <select name="bed_description_id">
      @foreach($bedDescriptions as $bedDescription)
        <option value="{{ $bedDescription->id }}"{{ old('bed_description_id', $bedSize->bed_description_id) == $bedDescription->id ? ' selected' : '' }}>
          {{ $bedDescription->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="details">
      Details
    </label>
    <textarea name="details" cols="30" rows="10">{{ old('details', $bedSize->details) }}</textarea>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($bedSize->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>