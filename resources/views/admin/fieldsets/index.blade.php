@extends('layouts.admin')

@section('title', "Fieldsets")

@section('page-title', "Fieldsets")

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.fieldsets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Fieldset
  </a>
@endsection

@section('page')
  @include('admin.fieldsets._filter-form')

  @if($fieldsets->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Fields</td>
        <td>Clone</td>
        <td>Actions</td>
      </thead>
      @foreach($fieldsets as $fieldset)
        <tr>
          <td>{{ $fieldset->id }}</td>
          <td>{{ $fieldset->name }}</td>
          <td>
            <a href="{{ route('admin.fieldsets.fields.index', $fieldset) }}">
              @choice('forms.fields', $fieldset->fields_count)
            </a>
          </td>
          <td>
            <div>
              <form action="{{ route('admin.fieldsets.clones.store', $fieldset) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-greeen-700 text-white py-2 px-4 rounded-full inline-block ml-1">
                  Clone
                </button>
              </form>
            </div>
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.fieldsets.edit', [
                'fieldset' => $fieldset,
              ]),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.fieldsets.destroy', [
                'fieldset' => $fieldset,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection