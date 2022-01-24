@extends('layouts.admin')

@section('title', "Email Recipients")

@section('page-title', "Email Recipients")

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.email-recipients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Email Recipient
  </a>
@endsection

@section('page')
  @include('admin.email-recipients._filter-form')

  @if($emailRecipients->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Email</td>
        <td>Receives Vehicle Enquiry</td>
        <td>Actions</td>
      </thead>
      @foreach($emailRecipients as $emailRecipient)
        <tr>
          <td>{{ $emailRecipient->id }}</td>
          <td>{{ $emailRecipient->name }}</td>
          <td>{{ $emailRecipient->email }}</td>
          <td>{{ $emailRecipient->receives_vehicle_enquiry ? 'Y' : 'N' }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.email-recipients.edit', $emailRecipient),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.email-recipients.destroy', $emailRecipient),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection