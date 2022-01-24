<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($site->exists)
    @method('put')
  @endif
  @csrf

  @include('admin._partials.redirect-input')

  <div>
    <label for="country">
      Country
    </label>
    <input name="country" value="{{ old("country", $site->country) }}" type="text" placeholder="Country" required>
  </div>

  <div>
    <label for="campaign_monitor_list_id">
      Campaign Monitor List ID
    </label>
    <input name="campaign_monitor_list_id" value="{{ old("campaign_monitor_list_id", $site->campaign_monitor_list_id) }}" type="text" placeholder="Campaign Monitor List ID">
  </div>

  <div>
    <label for="phone_number">
      Phone Number
    </label>
    <input name="phone_number" value="{{ old("phone_number", $site->phone_number) }}" type="text" placeholder="Phone Number">
  </div>

  <div>
    <label for="footer_content">Footer Content <span class="text-sm">e.g. Address</span></label>
    <textarea name="footer_content" cols="30" rows="10">{{ old('footer_content', $site->footer_content) }}</textarea>
  </div>

  <fieldset>
    <legend>Flag</legend>

    @foreach ($flags as $flag)
      <div class="w-20 mb-2">
        <label>
          <div class="flex">
            <input type="radio" name="flag" value="{{ $flag }}"{{ old('flag', $site->flag) == $flag ? ' checked' : '' }}>
            <img src="/images/flags/{{ $flag }}" class="ml-2 border border-black"/>
          </div>
        </label>
      </div>
    @endforeach
  </fieldset>

  <div>
    <label for="subdomain">
      Subdomain
    </label>
    <input name="subdomain" value="{{ old("subdomain", $site->subdomain) }}" type="text" placeholder="Subdomain" required>
  </div>

  <div>
    <label for="timezone">Timezone</label>
    <select name="timezone" id="timezone">
      @foreach (DateTimeZone::listIdentifiers() as $timezone)
        <option value="{{ $timezone }}"{{ old('timezone', $site->timezone) == $timezone ? ' selected' : '' }}>{{ $timezone }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="is_default" value="0">
      <input name="is_default" value="1" type="checkbox"{{ old('is_default', $site->is_default) ? ' checked' : ''}}>
      <span class="ml-2">
        Is default
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="has_stock" value="0">
      <input name="has_stock" value="1" type="checkbox"{{ old('has_stock', $site->has_stock) ? ' checked' : ''}}>
      <span class="ml-2">
        Has stock
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_opening_times_and_telephone_number" value="0">
      <input name="show_opening_times_and_telephone_number" value="1" type="checkbox"{{ old('show_opening_times_and_telephone_number', $site->show_opening_times_and_telephone_number) ? ' checked' : ''}}>
      <span class="ml-2">
        Show opening times and telephone number
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="display_exclusive_manufacturers_separately" value="0">
      <input name="display_exclusive_manufacturers_separately" value="1" type="checkbox"{{ old('display_exclusive_manufacturers_separately', $site->display_exclusive_manufacturers_separately) ? ' checked' : ''}}>
      <span class="ml-2">
        Display exclusive manufacturers separately
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_buy_tab_on_new_model_pages" value="0">
      <input name="show_buy_tab_on_new_model_pages" value="1" type="checkbox"{{ old('show_buy_tab_on_new_model_pages', $site->show_buy_tab_on_new_model_pages) ? ' checked' : ''}}>
      <span class="ml-2">
        Show buy tab on new model pages
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_offers_tab_on_new_model_pages" value="0">
      <input name="show_offers_tab_on_new_model_pages" value="1" type="checkbox"{{ old('show_offers_tab_on_new_model_pages', $site->show_offers_tab_on_new_model_pages) ? ' checked' : ''}}>
      <span class="ml-2">
        Show offers tab on new model pages
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_dealer_ranges" value="0">
      <input name="show_dealer_ranges" value="1" type="checkbox"{{ old('show_dealer_ranges', $site->show_dealer_ranges) ? ' checked' : ''}}>
      <span class="ml-2">
        Show dealer ranges
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_live_chat" value="0">
      <input name="show_live_chat" value="1" type="checkbox"{{ old('show_live_cheat', $site->show_live_chat) ? ' checked' : ''}}>
      <span class="ml-2">
        Show live chat
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_social_icons" value="0">
      <input name="show_social_icons" value="1" type="checkbox"{{ old('show_social_icons', $site->show_social_icons) ? ' checked' : ''}}>
      <span class="ml-2">
        Show social icons
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_accreditation_icons" value="0">
      <input name="show_accreditation_icons" value="1" type="checkbox"{{ old('show_accreditation_icons', $site->show_accreditation_icons) ? ' checked' : ''}}>
      <span class="ml-2">
        Show accreditation icons
      </span>
    </label>
  </div>

  <div>
    <label class="inline-flex items-center">
      <input type="hidden" name="show_footer_content" value="0">
      <input name="show_footer_content" value="1" type="checkbox"{{ old('show_footer_content', $site->show_footer_content) ? ' checked' : ''}}>
      <span class="ml-2">
        Show footer address
      </span>
    </label>
  </div>

  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($site->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>