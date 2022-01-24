<div>
  <label for="{{ $fieldName }}">
    Site
  </label>
  <select name="{{ $fieldName }}" required>
    @foreach($sites as $site)
      <option value="{{ $site->id }}"{{ old('site_id', $currentSiteId) == $site->id ? ' selected': '' }}>
        {{ $site->country }}
      </option>
    @endforeach
  </select>
</div>