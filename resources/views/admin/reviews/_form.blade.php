<form method="POST" action="{{ $url }}" enctype="multipart/form-data" class="admin-form">
  @include('admin._partials.errors')

  @if ($review->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="title">
      Title
    </label>
    <input name="title" value="{{ old('title', $review->title) }}" type="text" placeholder="Title" required>
  </div>

  <div>
    <label for="review_category_id">
      Category
    </label>
    <select name="review_category_id" required>
      @foreach($categories as $category)
        <option value="{{ $category->id }}"{{ old('review_category_id', $review->review_category_id) == $category->id ? ' selected': '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="magazine">
      Magazine
    </label>
    <input name="magazine" value="{{ old('magazine', $review->magazine) }}" type="text" placeholder="Magazine" required>
  </div>

  <div>
    <label for="date">Date</label>
    <input name="date" type="date" value="{{ old('date', $review->date ? $review->date->format('Y-m-d') : '') }}">
  </div>

  <div>
    <label for="text">
      Excerpt
    </label>
    <textarea rows="3" name="text" placeholder="Text">{{ old('text', $review->text) }}</textarea>
  </div>

  <div class="grid grid-cols-2 gap-5">
    <div>
      <label for="link">
        Link
      </label>
      <input type="text" name="link" value="{{ old('link', $review->link ?? '') }}" placeholder="Link URL">
    </div>

    <div>
      <label for="position">
        Position
      </label>
      <input type="number" name="position" value="{{ old('position', $review->position ?? 0) }}" step="1" placeholder="Optional">
    </div>
  </div>

  <div class="grid grid-cols-2 gap-5">
    <div>
      <p class="mb-2 font-bold text-sm" style="color:#4a5568;">Image</p>
      @if ($review->hasMedia('image'))
        <div>
          <img src="{{ $review->getFirstMediaUrl('image', 'thumb') }}">
        </div>
      @endif
      <input name="image" type="file" value="{{ old('image', '') }}">
      @if ($errors->has('image'))
        <p class="text-red-500 text-xs italic">{{ $errors->first('image') }}</p>
      @endif
    </div>

    <div>
      <p class="mb-1 font-bold" style="color:#4a5568;">Review File</p>
      @if ($review->hasMedia('review_file'))
        <div>
          <a href="{{ $review->getFirstMediaUrl('review_file') }}" target="_blank" rel="noopener" rel="noreferrer">
            {{ $review->getFirstMediaUrl('review_file') }}
          </a>
        </div>
      @endif
      <input name="review_file" type="file" value="{{ old('review_file', '') }}">
      @if ($errors->has('review_file'))
        <p class="text-red-500 text-xs italic">{{ $errors->first('review_file') }}</p>
      @endif
    </div>
  </div>

  <div>
    <label for="dealer_id">
      Dealer
    </label>
    <select name="dealer_id">
      <option value=""{{ old('dealer_id', $review->dealer_id) == '' ? ' selected' : ''}}>None</option>
      @foreach($dealers as $dealer)
        <option value="{{ $dealer->id }}"{{ old('dealer_id', $review->dealer_id) == $dealer->id ? ' selected': '' }}>
          {{ $dealer->name }}
        </option>
      @endforeach
    </select>
  </div>

  @include('admin._partials.site-checkboxes', [
    'sites' => $sites,
    'objectSiteIds' => $review->sites()->pluck('id')->toArray(),
  ])

  @include('admin._partials.caravan-and-motorhome-ranges-checkboxes', [
    'caravanRanges' => $caravanRanges,
    'caravanRangeIds' => $caravanRangeIds,
    'motorhomeRanges' => $motorhomeRanges,
    'motorhomeRangeIds' => $motorhomeRangeIds,
  ])

  <div class="grid grid-cols-2 gap-5">
    <div>
      <label for="published_at">
        Published At
      </label>
      <input name="published_at" type="date" value="{{ old('published_at', $review->published_at ? $review->published_at->format('Y-m-d') :'') }}">
    </div>

      <div>
      <label for="expired_at">
        Expiry At
      </label>
      <input name="expired_at" type="date" value="{{ old('expired_at', $review->expired_at ? $review->expired_at->format('Y-m-d') :'') }}">
    </div>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($review->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
