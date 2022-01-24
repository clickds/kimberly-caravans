@extends('layouts.admin')

@section('title', 'Employees')

@section('page-title', 'Employees')

@section('page-actions')
  <a href="{{ route('admin.dealers.employees.create', $dealer) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Employee</a>
@endsection

@section('page')
  @if($employees->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Role</td>
        <td>Phone</td>
        <td>Email</td>
        <td>Image</td>
        <td>Position</td>
        <td>Actions</td>
      </thead>
      @foreach($employees as $employee)
        <tr>
          <td>{{ $employee->id }}</td>
          <td>{{ $employee->name }}</td>
          <td>{{ $employee->role }}</td>
          <td>{{ $employee->phone }}</td>
          <td>{{ $employee->email }}</td>
          <td><img src="{{ $employee->getFirstMediaUrl('image', 'thumb') }}" /></td>
          <td>{{ $employee->position }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => route('admin.dealers.employees.edit', [
                'dealer' => $dealer,
                'employee' => $employee,
              ]),
              'destroy' => route('admin.dealers.employees.destroy', [
                'dealer' => $dealer,
                'employee' => $employee,
              ])
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection