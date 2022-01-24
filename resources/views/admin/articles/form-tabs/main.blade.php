<fieldset class="mb-2">
  <legend>Article Categories</legend>

  <div class="flex -mx-2">
    @foreach($articleCategories as $articleCategory)
      <div class="mx-2 w-1/4">
        <label>
          <input type="checkbox" name="article_category_ids[]" value="{{ $articleCategory->id }}"
            {{ in_array($articleCategory->id, old('article_category_ids', $articleCategoryIds)) ? ' checked' : '' }}
          >
          {{ $articleCategory->name }}
        </label>
      </div>
    @endforeach
  </div>
</fieldset>

<div class="mb-2">
  <label for="title">
    Title
  </label>
  <input name="title" value="{{ old("title", $article->title) }}" type="text" placeholder="Title" required>
</div>

<div class="mb-2">
  <label for="image">
    Image
  </label>
  @if($article->hasMedia('image'))
    <img class="my-2" src="{{ $article->getFirstMediaUrl('image', 'thumb') }}">
  @endif
  <input name="image" type="file" value="{{ old('image', '') }}">
  @if ($errors->has('image'))
    <p class="text-red-500 text-xs italic">{{ $errors->first('image') }}</p>
  @endif
</div>

<div class="mb-2">
  <label for="excerpt">
    Excerpt
  </label>
  <textarea rows="3" name="excerpt">{{ old('excerpt', $article->excerpt) }}</textarea>
</div>

<div class="mb-2">
  <label for="date">Date</label>
  <input name="date" type="date" value="{{ old('date', $article->date ? $article->date->format('Y-m-d') : date('Y-m-d')) }}" placeholder="Published At">
</div>

<div class="mb-2">
  <label for="type">Type</label>
  <select name="type">
    @foreach ($types as $type)
      <option value="{{ $type }}"
        {{ old('type', $article->type) == $type ? ' selected' : '' }}>
        {{ $type }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-2">
  <label for="style">Style</label>
  <select name="style">
    @foreach ($styles as $style)
      <option value="{{ $style }}"
        {{ old('style', $article->style) == $style ? ' selected' : '' }}>
        {{ $style }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-2">
  <label for="dealer_id">Dealer</label>
  <select name="dealer_id">
    <option value="">None</option>
    @foreach ($dealers as $dealer)
      <option value="{{ $dealer->id }}"
        {{ old('dealer_id', $article->dealer_id) == $dealer->id ? ' selected' : '' }}>
        {{ $dealer->name }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-2">
  <label for="published_at">
    Published At
  </label>
  <input name="published_at" type="date" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d') : date('Y-m-d')) }}" placeholder="Published At">
</div>

<div class="mb-2">
  <label for="expired_at">
    Expired At
  </label>
  <input name="expired_at" type="date" value="{{ old('expired_at', $article->expired_at) }}" placeholder="Expired At">
</div>

<div>
  <input type="hidden" name="live" value="0">
  <label for="live">Live</label>
  <input id="live" name="live" type="checkbox" value="1"{{ old('live', $article->live) ? ' checked' : '' }}>
</div>
