@push('header-scripts')
  {!! htmlScriptTagJsApi() !!}
@endpush

<form method="post" action="{{ $action }}" class="vacancy-application-form">
  @csrf

  @if($pageFacade->getDealers()->count() > 0)
    @if($pageFacade->getDealers()->count() === 1)
      <input type="hidden" name="dealer_id" value="{{ $pageFacade->getDealers()->first()->id }}" />
    @else
      <fieldset>
        <legend>Preferred Dealer</legend>

        <div class="field-group">
          <div class="input-group input-row">
            <label for="references">Which location are you applying to work at?</label>
            <select name="dealer_id">
              @foreach($pageFacade->getDealers() as $dealer)
                <option value="{{ $dealer->id }}" {{ old('dealer') === $dealer->id ? 'selected' : '' }}>{{ $dealer->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </fieldset>
    @endif
  @endif

  <fieldset>
    <legend>Applicant Details</legend>

    <div class="field-group">
      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-3">
        <div class="input-group">
          <label for="title"><sup>*</sup> Title</label>
          <input type="text" name="title" value="{{ old('title', '') }}" required />
        </div>
        <div class="input-group">
          <label for="first_name"><sup>*</sup> First name</label>
          <input type="text" name="first_name" value="{{ old('first_name', '') }}" required>
        </div>

        <div class="input-group">
          <label for="last_name"><sup>*</sup> Last name</label>
          <input type="text" name="last_name" value="{{ old('last_name', '') }}" required>
        </div>
      </div>

      <div class="input-row input-group">
        <label for="address"><sup>*</sup> Address</label>
        <textarea name="address" required>{{ old('address', '') }}</textarea>
      </div>

      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-2">
        <div class="input-group">
          <label for="nationality"><sup>*</sup> Nationality</label>
          <input type="text" name="nationality" value="{{ old('nationality', '') }}" required>
        </div>

        <div class="input-group">
          <label for="require_permission_to_work_in_uk"><sup>*</sup> Do you require permission to work in the UK?</label>
          <div class="radio-group">
            <span class="mr-1">Yes</span><input type="radio" name="require_permission_to_work_in_uk" value="1" required class="mr-3" {{ '1' == old('require_permission_to_work_in_uk') ? 'checked' : '' }}>
            <span class="mr-1">No</span><input type="radio" name="require_permission_to_work_in_uk" value="0" required  {{ '0' == old('require_permission_to_work_in_uk') ? 'checked' : '' }}>
          </div>
        </div>
      </div>

      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-2">
        <div class="input-group">
          <label for="telephone_number"><sup>*</sup> Telephone number</label>
          <input type="text" name="telephone_number" value="{{ old('telephone_number', '') }}" required>
        </div>

        <div class="input-group">
          <label for="mobile_number">Mobile Number</label>
          <input name="mobile_number" type="text" value="{{ old('mobile_number', '') }}" />
        </div>
      </div>

      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-2">
        <div class="input-group">
          <label for="number_of_dependents"><sup>*</sup> Number of dependants</label>
          <input type="number" name="number_of_dependents" value="{{ old('number_of_dependents', '') }}" required>
        </div>

        <div class="input-group">
          <label for="have_own_transport"><sup>*</sup> Do you have your own transport?</label>
          <div class="radio-group">
            <span class="mr-1">Yes</span><input type="radio" name="have_own_transport" value="1" required class="mr-3" {{ '1' == old('have_own_transport') ? 'checked' : '' }}>
            <span class="mr-1">No</span><input type="radio" name="have_own_transport" value="0" required  {{ '0' == old('have_own_transport') ? 'checked' : '' }}>
          </div>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Current Employment</legend>

    <div class="field-group">
      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-2">
        <div class="input-group">
          <label for="currently_employed_by">Are you currently employed, if so with which company?</label>
          <input type="text" name="currently_employed_by" value="{{ old('currently_employed_by', '') }}" />
        </div>
        <div class="input-group">
          <label for="currently_position">What position do you hold there?</label>
          <input type="text" name="current_position" value="{{ old('current_position', '') }}" />
        </div>
      </div>

      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-2">
        <div class="input-group">
          <label for="weeks_notice_required">If employed, how many weeks notice are you required to give your present employer?</label>
          <input type="number" name="weeks_notice_required" value="{{ old('weeks_notice_required', '') }}" />
        </div>
        <div class="input-group">
          <label for="marquis_employee_reference_name">Do you know any present employee of Marquis who would provide you with a reference if you have no objections?</label>
          <input type="text" name="marquis_employee_reference_name" value="{{ old('marquis_employee_reference_name', '') }}" />
        </div>
      </div>

      <div class="input-group input-row">
        <label for="seeking_employment_change_reason">Why are you seeking a change of employment?</label>
        <textarea name="seeking_employment_change_reason">{{ old('seeking_employment_change_reason', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="conviction_details">Other than spent convictions, have you ever been convicted of a criminal offence including motoring offences, if so give details?</label>
        <textarea name="conviction_details">{{ old('conviction_details', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="booked_holiday_details">Have you any holidays booked for the forthcoming year, if so when?</label>
        <textarea name="booked_holiday_details">{{ old('booked_holiday_details', '') }}</textarea>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Previous Employment</legend>
    <employment-history :initial-employment-history='@json(old('employment_history', []))' />
  </fieldset>

  <fieldset>
    <legend>General Health</legend>

    <div class="field-group">
      <div class="input-group input-row">
        <label for="have_any_disabilities"><sup>*</sup> Do you have any disabilities?</label>
        <div class="radio-group">
          <span class="mr-1">Yes</span><input type="radio" name="have_any_disabilities" value="1" required class="mr-3"  {{ '1' == old('have_any_disabilities') ? 'checked' : '' }}>
          <span class="mr-1">No</span><input type="radio" name="have_any_disabilities" value="0" required {{ '0' == old('have_any_disabilities') ? 'checked' : '' }}>
        </div>
      </div>

      <div class="input-group input-row">
        <label for="disability_details">If yes, please provide more information</label>
        <textarea name="disability_details">{{ old('disability_details', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="wear_glasses_or_contacts"><sup>*</sup> Do you wear spectacles/contact lenses at work?</label>
        <div class="radio-group">
          <span class="mr-1">Yes</span><input type="radio" name="wear_glasses_or_contacts" value="1" required class="mr-3" {{ '1' == old('wear_glasses_or_contacts') ? 'checked' : '' }}>
          <span class="mr-1">No</span><input type="radio" name="wear_glasses_or_contacts" value="0" required {{ '0' == old('wear_glasses_or_contacts') ? 'checked' : '' }}>
        </div>
      </div>

      <div class="input-group input-row">
        <label for="glasses_or_contacts_details">If yes, please specify</label>
        <input type="text" name="glasses_or_contacts_details" value="{{ old('glasses_or_contact_details', '') }}" />
      </div>

      <div class="input-group input-row">
        <label for="receiving_medical_treatment"><sup>*</sup> Are you currently receiving any medical treatment?</label>
        <div class="radio-group">
          <span class="mr-1">Yes</span><input type="radio" name="receiving_medical_treatment" value="1" required class="mr-3" {{ '1' == old('receiving_medical_treatment') ? 'checked' : '' }}>
          <span class="mr-1">No</span><input type="radio" name="receiving_medical_treatment" value="0" required {{ '0' == old('receiving_medical_treatment') ? 'checked' : '' }}>
        </div>
      </div>

      <div class="input-group input-row">
        <label for="medical_treatment_details">If yes, please provide more information</label>
        <textarea name="medical_treatment_details">{{ old('medical_treatment_details', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="smoker"><sup>*</sup> Are you a smoker?</label>
        <div class="radio-group">
          <span class="mr-1">Yes</span><input type="radio" name="smoker" value="1" required class="mr-3" {{ '1' == old('smoker') ? 'checked' : '' }}>
          <span class="mr-1">No</span><input type="radio" name="smoker" value="0" required {{ '0' == old('smoker') ? 'checked' : '' }}>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Qualifications</legend>

    <div class="field-group">
      <div class="input-group input-row">
        <label for="courses_and_certificates">Please give any details of an courses you have attended and any certificates you have obtained</label>
        <textarea name="courses_and_certificates">{{ old('courses_and_certificates', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="practical_experience">Please give details of any practical experience you have gained, and periods of time you have spent at that type of work</label>
        <textarea name="practical_experience">{{ old('practical_experience', '') }}</textarea>
      </div>

      <div class="input-group input-row">
        <label for="hobbies_and_interests">Please list any hobbies or sports you are interested in</label>
        <textarea name="hobbies_and_interests">{{ old('hobbies_and_interests', '') }}</textarea>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>References</legend>

    <div class="field-group">
      <div class="input-group input-row">
        <label for="references">Do you have any references available, if so please give names of previous employers who may give you a reference if required</label>
        <textarea name="references">{{ old('references', '') }}</textarea>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Submit Application</legend>

    <div class="field-group">
      <div class="input-row input-group">
        {!! htmlFormSnippet() !!}
      </div>

      <div class="input-group">
        <input type="submit" value="Submit" class="cursor-pointer" />
      </div>
    </div>
  </fieldset>
</form>
