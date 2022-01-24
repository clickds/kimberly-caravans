@extends('layouts.admin')

@section('title', 'Show Vacancy Application')

@section('page-title', 'Show Vacancy Application')

@section('page')
  <div>
    <div>
      <h2 class="mb-5">{{ $vacancy->title }} Application</h2>
    </div>

    @if($vacancyApplication->dealer)
      <h3>Location Applying For</h3>
      <dl class="admin-description-list">
        <dt>Dealer</dt>
        <dd>{{ $vacancyApplication->dealer->name }}</dd>
      </dl>
    @endif

    <h3>Applicant Details</h3>
    <dl class="admin-description-list mb-10">
      <dt>Title</dt>
      <dd>{{ $vacancyApplication->title }}</dd>

      <dt>First name</dt>
      <dd>{{ $vacancyApplication->first_name }}</dd>

      <dt>Last name</dt>
      <dd>{{ $vacancyApplication->last_name }}</dd>

      <dt>Address</dt>
      <dd>{!! $vacancyApplication->address ? nl2br($vacancyApplication->address) : 'N/A' !!}</dd>

      <dt>Nationality</dt>
      <dd>{{ $vacancyApplication->nationality }}</dd>

      <dt>Do you require permission to work in the UK?</dt>
      <dd>{{ $vacancyApplication->require_permission_to_work_in_uk ? 'Yes' : 'No' }}</dd>

      <dt>Telephone number</dt>
      <dd>{{ $vacancyApplication->telephone_number }}</dd>

      <dt>Mobile number</dt>
      <dd>{{ $vacancyApplication->mobile_number ?? 'N/A' }}</dd>

      <dt>Number of dependants</dt>
      <dd>{{ $vacancyApplication->number_of_dependents }}</dd>

      <dt>Do you have your own transport?</dt>
      <dd>{{ $vacancyApplication->have_own_transport ? 'Yes' : 'No' }}</dd>
    </dl>

    <h3>Current Employment</h3>
    <dl class="admin-description-list mb-10">
      <dt>Are you currently employed, if so with which company?</dt>
      <dd>{{ $vacancyApplication->currently_employed_by ?? 'N/A' }}</dd>

      <dt>What position do you hold there?</dt>
      <dd>{{ $vacancyApplication->current_position ?? 'N/A' }}</dd>

      <dt>If employed, how many weeks notice are you required to give your present employer?</dt>
      <dd>{{ $vacancyApplication->weeks_notice_required ?? 'N/A' }}</dd>

      <dt>Do you know any present employee of Marquis who would provide you with a reference if you have no objections?</dt>
      <dd>{{ $vacancyApplication->marquis_employee_reference_name ?? 'N/A' }}</dd>

      <dt>Why are you seeking a change of employment?</dt>
      <dd>{!! $vacancyApplication->seeking_employment_change_reason ? nl2br($vacancyApplication->seeking_employment_change_reason) : 'N/A' !!}</dd>

      <dt>Other than spent convictions, have you ever been convicted of a criminal offence including motoring offences, if so give details?</dt>
      <dd>{!! $vacancyApplication->conviction_details ? nl2br($vacancyApplication->conviction_details) : 'N/A' !!}</dd>

      <dt>Have you any holidays booked for the forthcoming year, if so when?</dt>
      <dd>{!! $vacancyApplication->booked_holiday_details ? nl2br($vacancyApplication->booked_holiday_details) : 'N/A' !!}</dd>
    </dl>

    <h3>Previous Employment</h3>
    <dl class="admin-description-list mb-10">
      @foreach($vacancyApplication->employmentHistory as $previousEmployment)
        <dt>Position</dt>
        <dd>{{ $previousEmployment->position }}</dd>
        <dt>Start date</dt>
        <dd>{{ $previousEmployment->start_date->format('d-m-Y') }}</dd>
        <dt>End date</dt>
        <dd>{{ $previousEmployment->end_date ? $previousEmployment->end_date->format('Y-m-d') : 'N/A' }}</dd>
        <dt>Name and address of employer</dt>
        <dd>{!! $vacancyApplication->employer_details ? nl2br($vacancyApplication->employer_details) : 'N/A' !!}</dd>
        <dt>Reasons for leaving</dt>
        <dd>{!! $vacancyApplication->reasons_for_leaving ? nl2br($vacancyApplication->reasons_for_leaving) : 'N/A' !!}</dd>

        @if(!$loop->first)
          <hr>
        @endif
      @endforeach
    </dl>

    <h3>General Health</h3>
    <dl class="admin-description-list mb-10">
      <dt>Do you have any disabilities?</dt>
      <dd>{{ $vacancyApplication->have_any_disabilities ? 'Yes' : 'No' }}</dd>

      <dt>If yes, please provide more information</dt>
      <dd>{!! $vacancyApplication->have_any_disabilities ? nl2br($vacancyApplication->have_any_disabilities) : 'N/A' !!}</dd>

      <dt>Do you wear spectacles/contact lenses at work?</dt>
      <dd>{{ $vacancyApplication->wear_glasses_or_contacts ? 'Yes' : 'No' }}</dd>

      <dt>If yes, please specify</dt>
      <dd>{{ $vacancyApplication->glasses_or_contacts_details ?? 'N/A'}}</dd>

      <dt>Are you currently receiving any medical treatment?</dt>
      <dd>{{ $vacancyApplication->receiving_medical_treatment ? 'Yes' : 'No' }}</dd>

      <dt>If yes, please provide more information</dt>
      <dd>{!! $vacancyApplication->medical_treatment_information ? nl2br($vacancyApplication->medical_treatment_information) : 'N/A' !!}</dd>

      <dt>Are you a smoker?</dt>
      <dd>{{ $vacancyApplication->smoker ? 'Yes' : 'No' }}</dd>
    </dl>

    <h3>Qualifications</h3>
    <dl class="admin-description-list mb-10">
      <dt>Please give any details of an courses you have attended and any certificates you have obtained</dt>
      <dd>{!! $vacancyApplication->courses_and_certificates ? nl2br($vacancyApplication->courses_and_certificates) : 'N/A' !!}</dd>

      <dt>Please give details of any practical experience you have gained, and periods of time you have spent at that type of work</dt>
      <dd>{!! $vacancyApplication->practical_experience ? nl2br($vacancyApplication->practical_experience) : 'N/A' !!}</dd>

      <dt>Please list any hobbies or sports you are interested in</dt>
      <dd>{!! $vacancyApplication->hobbies_and_interests ? nl2br($vacancyApplication->hobbies_and_interests) : 'N/A' !!}</dd>
    </dl>

    <h3>References</h3>
    <dl class="admin-description-list">
      <dt>Do you have any references available, if so please give names of previous employers who may give you a reference if required</dt>
      <dd>{!! $vacancyApplication->references ? nl2br($vacancyApplication->references) : 'N/A' !!}</dd>
    </dl>
  </div>
@endsection