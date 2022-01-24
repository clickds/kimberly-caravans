<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($event->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $event->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="dealer_id">
      Dealer (Dealer location will be shown on the map)
    </label>
    <select name="dealer_id">
      <option value=""></option>
      @foreach($dealers as $dealer)
        <option value="{{ $dealer->id }}" {{ old('dealer_id', $event->dealer_id) === $dealer->id ? 'selected' : '' }}>{{ $dealer->name }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="event_location_id">
      Location (Event location will be shown on the map. Dealer location will take priority if both are set.)
    </label>
    <select name="event_location_id">
      <option value=""></option>
      @foreach($locations as $location)
        <option value="{{ $location->id }}" {{ old('event_location_id', $event->event_location_id) === $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="Summary">
      Summary
    </label>
    <textarea rows="3" name="summary">{{ old('summary', $event->summary) }}</textarea>
  </div>

  <div>
    <label for="start_date">
      Start Date
    </label>
    <input name="start_date" type="date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" placeholder="Start Date">
  </div>

  <div>
    <label for="end_date">
      End Date
    </label>
    <input name="end_date" type="date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" placeholder="End Date">
  </div>

  <div>
    <label for="image">
      Image
    </label>
    <img class="w-1/5" src="{{ $event->getFirstMediaUrl('image') }}" />
    <input name="image" type="file" value="{{ old('image', '') }}">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($event->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>