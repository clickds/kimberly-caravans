<fieldset class="my-4 p-10 bg-gray-100 border">
  <legend>Seats</legend>
  <div class="grid grid-cols-4 gap-10">
    @foreach ($seats as $seat)
      <div>
        <label class="inline-flex items-center">
          <input name="seat_ids[]" value="{{ $seat->id }}" type="checkbox"{{ in_array($seat->id, old('seat_ids', $object->seats()->pluck('id')->toArray())) ? ' checked' : ''}}>
          <span class="ml-2">{{ $seat->number }}</span>
        </label>
      </div>
    @endforeach
  </div>
</fieldset>