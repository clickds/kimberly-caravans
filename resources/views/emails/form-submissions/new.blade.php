@extends('layouts.mailer')

@section('content')
<h1>{{ $form->name }} Form Submitted</h1>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  @foreach ($submission->submission_data as $label => $input)
    <tr>
      <th width="50%" style="text-align: left; vertical-align: top;">
        {{ $label }}
      </th>
      <td width="50%">
        @if (is_array($input))
          <ul>
            @foreach($input as $option)
              <li>{{ $option }}</li>
            @endforeach
          </ul>
        @elseif (is_bool($input))
          {{ $input === true ? 'Yes' : 'No' }}
        @elseif (filter_var($input, FILTER_VALIDATE_EMAIL))
          <a href="mailto:{{ $input }}">{{ $input }}</a>
        @elseif (filter_var($input, FILTER_VALIDATE_URL))
          <a href="{{ $input }}">{{ $input }}</a>
        @else
          {{ $input }}
        @endif
      </td>
    </tr>
  @endforeach
</table>
@endsection
