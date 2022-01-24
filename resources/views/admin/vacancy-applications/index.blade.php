@extends('layouts.admin')

@section('title', 'Vacancy Applications')

@section('page-title', 'Vacancy Applications')

@section('page')
  @include('admin.vacancy-applications._filter-form')

  @if($vacancyApplications->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
      <td>ID</td>
      <td>Dealer</td>
      <td>Title</td>
      <td>First Name</td>
      <td>Last Name</td>
      <td>Address</td>
      <td>Created At</td>
      <td class="text-center">Actions</td>
      </thead>
      @foreach($vacancyApplications as $vacancyApplication)
        <tr>
          <td>{{ $vacancyApplication->id }}</td>
          <td>{{ $vacancyApplication->dealer->name }}</td>
          <td>{{ $vacancyApplication->title }}</td>
          <td>{{ $vacancyApplication->first_name }}</td>
          <td>{{ $vacancyApplication->last_name }}</td>
          <td>{{ $vacancyApplication->address }}</td>
          <td>{{ $vacancyApplication->created_at->format('d-m-Y') }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'show' => route('admin.vacancies.vacancy-applications.show', ['vacancy' => $vacancyApplication->vacancy, 'vacancy_application' => $vacancyApplication]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection