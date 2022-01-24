@extends('layouts.mailer')

@section('content')
<h1>Stock Enquiry</h1>

<h3>Contact Details</h3>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Title</th>
    <td width="50%">{{ $vehicleEnquiry->title }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">First Name</th>
    <td width="50%">{{ $vehicleEnquiry->first_name }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Surname</th>
    <td width="50%">{{ $vehicleEnquiry->surname }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Email</th>
    <td width="50%">
      <a href="mailto:{{ $vehicleEnquiry->email }}">
        {{ $vehicleEnquiry->email }}
      </a>
    </td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Phone</th>
    <td width="50%">{{ $vehicleEnquiry->phone_number }}</td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">County</th>
    <td width="50%">{{ $vehicleEnquiry->county }}</td>
  </tr>
</table>

<h3>How can we help</h3>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Help Methods</th>
    <td width="50%">
      @if ($vehicleEnquiry->help_methods)
        <ul>
          @foreach($vehicleEnquiry->help_methods as $helpMethod)
            <li>
              {{ $helpMethod }}
            </li>
          @endforeach
        </ul>
      @else
        N/A
      @endif
    </td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Message</th>
    <td width="50%">
      {{ $vehicleEnquiry->message }}
    </td>
  </tr>
</table>

<h3>Keep in touch</h3>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Interested in</th>
    <td width="50%">
      @if ($vehicleEnquiry->interests)
        <ul>
          @foreach($vehicleEnquiry->interests as $interest)
            <li>
              {{ $interest }}
            </li>
          @endforeach
        </ul>
      @else
        N/A
      @endif
    </td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Communication preferences</th>
    <td width="50%">
      <ul>
        @if ($vehicleEnquiry->marketing_preferences)
          @foreach($vehicleEnquiry->marketing_preferences as $marketingPreference)
            <li>
              {{ $marketingPreference }}
            </li>
          @endforeach
        @else
            N/A
        @endif
      </ul>
    </td>
  </tr>
  <tr>
    <th width="50%" style="text-align: left; vertical-align: top;">Preferred Dealers</th>
    <td width="50%">
      <ul>
          @if ($vehicleEnquiry->dealers)
            @foreach($vehicleEnquiry->dealers as $dealer)
              <li>
                {{ $dealer->name }}
              </li>
            @endforeach
          @else
              None
          @endif
        </ul>
    </td>
  </tr>
</table>
@endsection