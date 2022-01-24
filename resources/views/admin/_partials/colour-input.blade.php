<fieldset>
  <legend>{{ $label }}</legend>

  <div class="flex flex-wrap -mx-2">
    @foreach ($colours as $colour => $humanisedColour)
      <div class="w-1/4 px-2">
        <label>
          <span class="h-4 w-4 border border-solid border-black inline-block bg-{{ $colour }}"></span>
          <input type="radio" name="{{ $name }}" value="{{ $colour }}"{{ $value == $colour ? ' checked' : '' }}>
          {{ $humanisedColour }}
        </label>
      </div>
    @endforeach
  </div>
 </fieldset>