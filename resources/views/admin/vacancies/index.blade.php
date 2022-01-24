@extends('layouts.admin')

@section('title', 'Vacancies')

@section('page-title', 'Vacancies')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.vacancies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New vacancy</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Vacancies Page'])
@endsection

@section('page')
  @include('admin.vacancies._filter-form')

  @if($vacancies->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Title</td>
        <td>Applications</td>
        <td>Published At</td>
        <td>Expires At</td>
        <td>Notification Email Address</td>
        <td>Pages</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($vacancies as $vacancy)
        <tr>
          <td>{{ $vacancy->id }}</td>
          <td>{{ $vacancy->title }}</td>
          <td><a href="{{ route('admin.vacancies.vacancy-applications.index', ['vacancy' => $vacancy]) }}">{{ $vacancy->applications_count }}</a></td>
          <td>{{ $vacancy->published_at ? $vacancy->published_at->format('d-m-Y') : '' }}</td>
          <td>{{ $vacancy->expired_at ? $vacancy->expired_at->format('d-m-Y') : '' }}</td>
          <td>{{ $vacancy->notification_email_address ?? 'None' }}</td>
          <td>
            @foreach ($vacancy->pages as $page)
              <a href="{{ $page->link() }}" class="block" target="_blank">{{ $page->slug }}</a>
            @endforeach
          </td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.vacancies.edit', $vacancy),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.vacancies.destroy', $vacancy)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection