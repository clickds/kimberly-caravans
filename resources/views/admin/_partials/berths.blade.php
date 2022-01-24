<fieldset class="my-4 p-10 bg-gray-100 border">
  <legend>Berths</legend>
  <div class="grid grid-cols-4 gap-10">
    @foreach ($berths as $berth)
      <div>
        <label class="inline-flex items-center">
          <input name="berth_ids[]" value="{{ $berth->id }}" type="checkbox"{{ in_array($berth->id, old('berth_ids', $object->berths()->pluck('id')->toArray())) ? ' checked' : ''}}>
          <span class="ml-2">{{ $berth->number }}</span>
        </label>
      </div>
    @endforeach
  </div>
</fieldset>