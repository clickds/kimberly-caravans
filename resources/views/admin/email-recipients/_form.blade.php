<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($emailRecipient->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $emailRecipient->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="email">
      Email
    </label>
    <input name="email" value="{{ old("email", $emailRecipient->email) }}" type="email" placeholder="Email" required>
  </div>

  <div>
    <input type="hidden" name="receives_vehicle_enquiry" value="0">
    <label for="receives_vehicle_enquiry">
      Receives Vehicle Enquiry
    </label>
    <input type="checkbox" name="receives_vehicle_enquiry"
      {{ old('receives_vehicle_enquiry', $emailRecipient->receives_vehicle_enquiry) ? 'checked' : '' }}
      id="receives_vehicle_enquiry" value="1">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($emailRecipient->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>