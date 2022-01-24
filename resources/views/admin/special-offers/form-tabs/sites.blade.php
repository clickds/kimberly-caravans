<fieldset class="border pt-5 pl-5 mb-3 bg-gray-100">
  <legend><b>Sites</b></legend>
    @foreach ($sites as $site)
    <div class="mb-2">
      <label class="inline-flex items-center">
        <input name="site_ids[]" value="{{ $site->id }}"
          type="checkbox"{{ in_array($site->id, old('site_ids', $currentSiteIds)) ? ' checked' : ''}}>
        <span class="ml-2">{{ $site->country }}</span>
      </label>
    </div>
    @endforeach
</fieldset>