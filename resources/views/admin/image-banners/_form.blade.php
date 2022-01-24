<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($imageBanner->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old("title", $imageBanner->title) }}" type="text" placeholder="Title" required>
  </div>

  <div>
    <label for="icon">Icon</label>
    <select name="icon" id="icon">
      @foreach ($icons as $icon)
        <option value="{{ $icon }}"{{ old('icon', $imageBanner->icon) === $icon ? ' selected' : ''}}>
          {{ $icon }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="text_alignment">Text Alignment</label>
    <select name="text_alignment" id="text_alignment">
      @foreach ($textAlignments as $value => $label)
        <option value="{{ $value }}"{{ old('text_alignment', $imageBanner->text_alignment) === $value ? ' selected' : ''}}>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  @include('admin._partials.colour-input', [
    'label' => 'Title Text Colour',
    'name' => 'title_text_colour',
    'colours' => $textColours,
    'value' => old('title_text_colour', $imageBanner->title_text_colour)
  ])

  @include('admin._partials.colour-input', [
    'label' => 'Title Background Colour',
    'name' => 'title_background_colour',
    'colours' => $backgroundColours,
    'value' => old('title_background_colour', $imageBanner->title_background_colour)
  ])

  <wysiwyg-field
    csrf-token="{{ csrf_token() }}"
    label="Content"
    name="content"
    initial-value="{{ old('content', $imageBanner->content) }}"
    assets-page-url="{{ route('admin.assets.index') }}">
  </wysiwyg-field>

  @include('admin._partials.colour-input', [
    'label' => 'Content Text Colour',
    'name' => 'content_text_colour',
    'colours' => $textColours,
    'value' => old('content_text_colour', $imageBanner->content_text_colour)
  ])

  @include('admin._partials.colour-input', [
    'label' => 'Content Background Colour',
    'name' => 'content_background_colour',
    'colours' => $backgroundColours,
    'value' => old('content_background_colour', $imageBanner->content_background_colour)
  ])

  @php
    $image = $imageBanner->getFirstMedia('image');
    $imageAlt = $image ? $image->name : null;
  @endphp
  <div>
    <label for="image">Image</label>
    <input type="file" name="image">
  </div>

  <div>
    <label for="image_alt">Image Alt</label>
    <input type="text" name="image_alt" value="{{ old('image_alt', $imageAlt) }}">
  </div>

  <div>
    <label for="position">Position</label>
    <input type="number" name="position" value="{{ old('position', $imageBanner->position) }}">
  </div>

  <div>
    <label for="published_at">
      Published At
    </label>
    <input name="published_at" type="date" value="{{ old('published_at', $imageBanner->published_at ? $imageBanner->published_at->format('Y-m-d') :'') }}">
  </div>

  <div>
    <label for="expired_at">
      Expired At
    </label>
    <input name="expired_at" type="date" value="{{ old('expired_at', $imageBanner->expired_at ? $imageBanner->expired_at->format('Y-m-d') : '' ) }}">
  </div>

  <div>
    <input type="hidden" name="live" value="0">
    <label>
      <input type="checkbox" name="live" value="1"{{ $imageBanner->live ? ' checked' : ''}}> Live
    </label>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($imageBanner->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>