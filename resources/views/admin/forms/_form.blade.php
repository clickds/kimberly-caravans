<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($form->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="name">
      Name
    </label>
    <input name="name" value="{{ old("name", $form->name) }}" type="text" placeholder="Name" required>
  </div>

  <div>
    <label for="type">
      Type
    </label>
    <select name="type" class="bg-gray-200 border border-gray-200 text-gray-700 px-3 rounded">
      @foreach($types as $type)
        <option value="{{ $type }}"{{ old('type', $form->type) == $type ? ' selected' : '' }}>{{ $type }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="email_to">
      Email To
    </label>
    <input name="email_to" value="{{ old("email_to", $form->email_to) }}" type="email" placeholder="Email To" required>
  </div>

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Successful Submission Message"
    name="successful_submission_message"
    initial-value="{{ old('successful_submission_message', $form->successful_submission_message) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  <fieldset-selection :current-fieldset-ids='@json(old('fieldset_ids', $form->fieldsets()->pluck('id')->toArray()))' :fieldsets='@json($fieldsets)'></fieldset-selection>

  <fieldset class="my-4">
    <legend>Carbon Copy Recipients</legend>

    <div class="flex flex-wrap -mx-2">
      @foreach ($emailRecipients as $emailRecipient)
        <div class="px-2 w-1/4">
          <label>
            <input type="checkbox" name="carbon_copy_ids[]" value="{{ $emailRecipient->id }}"
              {{ in_array($emailRecipient->id, old('carbon_copy_ids', $form->carbonCopyRecipients()->pluck('id')->toArray())) ? ' checked' : '' }}
            >
            {{ $emailRecipient->name }}
          </label>
        </div>
      @endforeach
    </div>
  </fieldset>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($form->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>