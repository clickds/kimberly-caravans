<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($vacancy->exists)
    @method('put')
  @endif

  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old("title", $vacancy->title) }}" type="text" placeholder="Title" required>
  </div>

  <div>
    <label for="salary">
      Salary
    </label>
    <input name="salary" value="{{ old("salary", $vacancy->salary) }}" type="text" placeholder="Salary (E.g. £10,000 per anum or £10 per hour)">
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Short Description (Shown on the listing page)"
    name="short_description"
    initial-value="{{ old('short_description', $vacancy->short_description) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Description"
    name="description"
    initial-value="{{ old('description', $vacancy->description) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <div>
    <fieldset class="border pt-5 pl-5 mb-3 bg-gray-100">
      <legend><b>Dealers</b></legend>
      @foreach ($dealers as $dealer)
        <div class="mb-2">
          <label class="inline-flex items-center">
            <input name="dealer_ids[]" value="{{ $dealer->id }}" type="checkbox"{{ in_array($dealer->id, old('dealer_ids', $vacancy->dealers()->pluck('id')->toArray())) ? ' checked' : ''}}>
            <span class="ml-2">{{ $dealer->name }}</span>
          </label>
        </div>
      @endforeach
    </fieldset>
  </div>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $vacancy->published_at ? $vacancy->published_at->format('Y-m-d') : '') }}" placeholder="Published At">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $vacancy->expired_at ? $vacancy->expired_at->format('Y-m-d') : '') }}" placeholder="Expired At">
  </div>

  <div>
    <label for="notification_email_address">
      Notification Email Address
    </label>
    <input name="notification_email_address" type="email" value="{{ old('notification_email_address', $vacancy->notification_email_address, '') }}" placeholder="Notification Email Address">
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($vacancy->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>