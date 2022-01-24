@extends('layouts.admin')

@section('title', "{$fieldset->name} Fields")

@section('page-title', "{$fieldset->name} Fields")

@section('page-actions')
  <a href="{{ route('admin.fieldsets.fields.create', $fieldset) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Field
  </a>
@endsection

@section('page')
  @if($fields->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Width</td>
        <td>Type</td>
        <td>Actions</td>
      </thead>
      @foreach($fields as $field)
        <tr>
          <td>{{ $field->id }}</td>
          <td>{{ $field->name }}</td>
          <td>{{ $field->width }}</td>
          <td>{{ $field->humanisedType() }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => route('admin.fieldsets.fields.edit', [
                'fieldset' => $fieldset,
                'field' => $field,
              ]),
              'destroy' => route('admin.fieldsets.fields.destroy', [
                'fieldset' => $fieldset,
                'field' => $field,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection