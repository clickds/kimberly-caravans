<form method="POST" action="{{ $url }}" class="admin-form">
  @csrf
  @if ($area->exists)
    @method('PUT')
  @endif
  @include('admin._partials.redirect-input')
  @include('admin._partials.errors')
  <div>
    <label for="name">
      Name
      <span class="text-xs">Used as tab name for tabbed areas</span>
    </label>
    <input name="name" type="text" placeholder="Name" value="{{ old('name', $area->name) }}" required>
  </div>

  <div class="flex -mx-2">
    <div class="px-2 w-full md:w-1/3">
      <label for="columns">
        Columns
      </label>
      <select name="columns">
        @foreach ($columns as $column)
          <option value="{{ $column }}"{{ old('columns', $area->columns) == $column ? ' selected' : '' }}>{{ $column }}</option>
        @endforeach
      </select>
    </div>

    <div class="px-2 w-full md:w-1/3">
      <label for="holder">
        Holder
      </label>
      <select name="holder">
        @foreach ($holders as $holder)
          <option value="{{ $holder }}"{{ old('holder', $area->holder) == $holder ? ' selected' : '' }}>{{ $holder }}</option>
        @endforeach
      </select>
    </div>

    <div class="px-2 w-full md:w-1/3">
      <label for="width">Width</label>
      <select name="width">
        @foreach ($widths as $width => $humanisedWidth)
          <option value="{{ $width }}"{{ old('width', $area->width) == $width ? ' selected' : '' }}>{{ $humanisedWidth }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="flex -mx-2">
    <div class="px-2 w-full md:1/3">
      <label for="heading_text_alignment">Heading Text Alignment</label>
      <select name="heading_text_alignment">
        @foreach($textAlignments as $value => $name)
          <option value="{{ $value }}"{{ old('heading_text_alignment', $area->heading_text_alignment) == $value ? ' selected' : '' }}>
            {{ $name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="px-2 w-full md:1/3">
      <label for="heading">
        Heading
      </label>
      <input name="heading" type="text" placeholder="Heading" value="{{ old('heading', $area->heading) }}">
    </div>

    <div class="px-2 w-full md:1/3">
      <label for="heading_type">
        Heading Type
      </label>
      <select name="heading_type">
        @foreach ($headingTypes as $headingType)
          <option value="{{ $headingType }}"{{ old('heading_type', $area->heading_type) == $headingType ? ' selected' : '' }}>{{ $headingType }}</option>
        @endforeach
      </select>
    </div>
  </div>

  @include('admin._partials.colour-input', [
    'label' => 'Background Colour',
    'name' => 'background_colour',
    'colours' => $backgroundColours,
    'value' => old('background_colour', $area->background_colour)
  ])

  <div class="flex -mx-2">
    <div class="px-2 w-full md:w-1/3">
      <label for="position">Position</label>
      <input type="number" name="position" value="{{ old('position', $area->position) }}">
    </div>

    <div class="px-2 w-full md:w-1/3">
      <label for="published_at">
        Published At
      </label>
      <input name="published_at" type="date" placeholder="Published At" value={{ old('published_at', $area->published_at )}}>
    </div>

    <div class="px-2 w-full md:w-1/3">
      <label for="expired_at">
        Expired At
      </label>
      <input name="expired_at" type="date" placeholder="Expired At" value={{ old('expired_at', $area->expired_at )}}>
    </div>
  </div>

  <div>
    <input type="hidden" name="live" value="0">
    <label for="live">Live</label>
    <input id="live" name="live" type="checkbox" value="1"{{ old('live', $area->live) ? ' checked' : '' }}>
  </div>

  <div>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if($area->exists)
        Update
      @else
        Create
      @endif
    </button>
  </div>
</form>