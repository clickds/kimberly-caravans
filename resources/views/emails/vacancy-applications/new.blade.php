@extends('layouts.mailer')

@section('content')
<h1>Vacancy Application - {{ $vacancyApplication->vacancy->title }}</h1>

@if($vacancyApplication->dealer)
  <h3>Location Applied For</h3>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Preferred Dealer</th>
      <td width="50%">{{ $vacancyApplication->dealer->name }}</td>
    </tr>
  </table>
@endif

<h3>Applicant Details</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Title</th>
    <td width="50%">{{ $vacancyApplication->title }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">First name</th>
    <td width="50%">{{ $vacancyApplication->first_name }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Last name</th>
    <td width="50%">{{ $vacancyApplication->last_name }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Address</th>
    <td width="50%">{!! $vacancyApplication->address ? nl2br($vacancyApplication->address) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Nationality</th>
    <td width="50%">{{ $vacancyApplication->nationality }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you require permission to work in the UK?</th>
    <td width="50%">{{ $vacancyApplication->require_permission_to_work_in_uk ? 'Yes' : 'No' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Telephone number</th>
    <td width="50%">{{ $vacancyApplication->telephone_number }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Mobile number</th>
    <td width="50%">{{ $vacancyApplication->mobile_number }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Number of dependants</th>
    <td width="50%">{{ $vacancyApplication->number_of_dependents }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you have your own transport?</th>
    <td width="50%">{{ $vacancyApplication->have_own_transport ? 'Yes' : 'No' }}</td>
  </tr>
</table>

<h3>Current Employment</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Are you currently employed, if so with which company?</th>
    <td width="50%">{{ $vacancyApplication->currently_employed_by ?? 'N/A' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">What position do you hold there?</th>
    <td width="50%">{{ $vacancyApplication->current_position ?? 'N/A' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">If employed, how many weeks notice are you required to give your present employer?</th>
    <td width="50%">{{ $vacancyApplication->weeks_notice_required ?? 'N/A' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you know any present employee of Marquis who would provide you with a reference if you have no objections?</th>
    <td width="50%">{{ $vacancyApplication->marquis_employee_reference_name ?? 'N/A' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Why are you seeking a change of employment?</th>
    <td width="50%">{!! $vacancyApplication->seeking_employment_change_reason ? nl2br($vacancyApplication->seeking_employment_change_reason) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Other than spent convictions, have you ever been convicted of a criminal offence including motoring offences, if so give details?</th>
    <td width="50%">{!! $vacancyApplication->conviction_details ? nl2br($vacancyApplication->conviction_details) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Have you any holidays booked for the forthcoming year, if so when?</th>
    <td width="50%">{!! $vacancyApplication->booked_holiday_details ? nl2br($vacancyApplication->booked_holiday_details) : 'N/A' !!}</td>
  </tr>
</table>

<h3>Previous Employment</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  @foreach($vacancyApplication->employmentHistory as $previousEmployment)
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Position</th>
      <td width="50%">{{ $previousEmployment->position }}</td>
    </tr>
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Start date</th>
      <td width="50%">{{ $previousEmployment->start_date->format('d-m-Y') }}</td>
    </tr>
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">End date</th>
      <td width="50%">{{ $previousEmployment->end_date ? $previousEmployment->end_date->format('Y-m-d') : 'N/A' }}</td>
    </tr>
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Name and address of employer</th>
      <td width="50%">{!! $vacancyApplication->employer_details ? nl2br($vacancyApplication->employer_details) : 'N/A' !!}</td>
    </tr>
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Resons for leaving</th>
      <td width="50%">{!! $vacancyApplication->reasons_for_leaving ? nl2br($vacancyApplication->reasons_for_leaving) : 'N/A' !!}</td>
    </tr>

  @endforeach
</table>

<h3>General Health</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you have any disabilities?</th>
    <td width="50%">{{ $vacancyApplication->have_any_disabilities ? 'Yes' : 'No' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">If yes, please provide more information</th>
    <td width="50%">{!! $vacancyApplication->have_any_disabilities ? nl2br($vacancyApplication->have_any_disabilities) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you wear spectacles/contact lenses at work?</th>
    <td width="50%">{{ $vacancyApplication->wear_glasses_or_contacts ? 'Yes' : 'No' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">If yes, please specify</th>
    <td width="50%">{{ $vacancyApplication->glasses_or_contacts_details ?? 'N/A'}}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Are you currently receiving any medical treatment?</th>
    <td width="50%">{{ $vacancyApplication->receiving_medical_treatment ? 'Yes' : 'No' }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">If yes, please provide more information</th>
    <td width="50%">{!! $vacancyApplication->medical_treatment_information ? nl2br($vacancyApplication->medical_treatment_information) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Are you a smoker?</th>
    <td width="50%">{{ $vacancyApplication->smoker ? 'Yes' : 'No' }}</td>
  </tr>
</table>

<h3>Qualifications</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Please give any details of an courses you have attended and any certificates you have obtained</th>
    <td width="50%">{!! $vacancyApplication->courses_and_certificates ? nl2br($vacancyApplication->courses_and_certificates) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Please give details of any practical experience you have gained, and periods of time you have spent at that type of work</th>
    <td width="50%">{!! $vacancyApplication->practical_experience ? nl2br($vacancyApplication->practical_experience) : 'N/A' !!}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Please list any hobbies or sports you are interested in</th>
    <td width="50%">{!! $vacancyApplication->hobbies_and_interests ? nl2br($vacancyApplication->hobbies_and_interests) : 'N/A' !!}</td>
  </tr>
</table>

<h3>References</h3>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top; padding-bottom: 10px;">Do you have any references available, if so please give names of previous employers who may give you a reference if required</th>
    <td width="50%">{!! $vacancyApplication->references ? nl2br($vacancyApplication->references) : 'N/A' !!}</td>
  </tr>
</table>
@endsection