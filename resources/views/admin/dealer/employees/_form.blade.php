<form method="POST" action="{{ $url }}" class="admin-form" enctype="multipart/form-data">
  @include('admin._partials.errors')

  @if ($employee->exists)
    @method('put')
  @endif
  @csrf

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $employee->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="role">
      Role
    </label>
    <input type="text" name="role" value="{{ old('role', $employee->role) }}" placeholder="Role" required>
  </div>

  <div>
    <label for="phone">
      Phone
    </label>
    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="Phone">
  </div>

  <div>
    <label for="email">
      Email
    </label>
    <input type="email" name="email" value="{{ old('email', $employee->email) }}" placeholder="Email">
  </div>

  <div>
    <label for="image">Image</label>
    <img src="{{ $employee->getFirstMediaUrl('image', 'thumb') }}" />
    <input name="image" type="file" value="{{ old('image', '') }}">
  </div>

  <div>
    <label for="position">
      Position
    </label>
    <input type="number" name="position" value="{{ old('position', $employee->position) }}" placeholder="Position">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($employee->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>