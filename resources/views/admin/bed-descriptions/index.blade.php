@extends('layouts.admin')

@section('title', 'Bed Descriptions')

@section('page-title', 'Bed Descriptions')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'bed description',
    'url'  => routeWithCurrentUrlAsRedirect('admin.bed-descriptions.create')
  ])
@endsection

@section('page')
  @include('admin.bed-descriptions._filter-form')

  @if($bedDescriptions->isNotEmpty())
    <table class="admin-table">
        <thead>
            <td>ID</td>
            <td>Name</td>
            <td class="text-center">Actions</td>
        </thead>
        @foreach($bedDescriptions as $bedDescription)
            <tr>
                <td>{{ $bedDescription->id }}</td>
                <td>{{ $bedDescription->name }}</td>
                <td>
                    @include('admin._partials.table-row-action-cell', [
                      'edit' => routeWithCurrentUrlAsRedirect('admin.bed-descriptions.edit', $bedDescription),
                      'destroy' => routeWithCurrentUrlAsRedirect('admin.bed-descriptions.destroy', $bedDescription)
                    ])
                </td>
            </tr>
        @endforeach
    </table>
  @endif
@endsection