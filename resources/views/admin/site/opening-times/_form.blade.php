<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($openingTime->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="day">
      Day
    </label>
    <select name="day">
      @foreach($days as $value => $label)
        <option value="{{ $value }}"{{ old('day', $openingTime->day) == $value ? ' selected' : '' }}>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="opens_at">Opens At</label>
    <input name="opens_at" type="time" value="{{ old('opens_at', $openingTime->opens_at->format('H:i')) }}" placeholder="Opens At" required>
  </div>

  <div>
    <label for="closes_at">Closes At</label>
    <input name="closes_at" type="time" value="{{ old('closes_at', $openingTime->closes_at->format('H:i')) }}" placeholder="Closes At" required>
  </div>

  <div>
    <input type="hidden" name="closed" value="0">
    <label>
      <input type="checkbox" name="closed" value="1"{{ $openingTime->closed ? ' checked' : ''}}> Closed
    </label>
  </div>

  <div class="flex items-center justify-between">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          @if ($openingTime->exists)
            @lang('global.update')
          @else
            @lang('global.create')
          @endif
      </button>
  </div>
</form>