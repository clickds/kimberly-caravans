@extends('layouts.admin')

@section('title', 'Buttons')

@section('page-title', 'Buttons')

@section('page-actions')
  @include('admin._partials.button', [
    'text' => 'New Button',
    'url' => route('admin.' . $pluralButtonableRouteName . '.buttons.create', $buttonable)
  ])
@endsection

@section('page')
  @if($buttons->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Colour</td>
        <td>Actions</td>
      </thead>
      @foreach($buttons as $button)
        <tr>
          <td>{{ $button->id }}</td>
          <td>{{ $button->name }}</td>
          <td>{{ $button->humanisedColourName() }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit'    => route('admin.' . $pluralButtonableRouteName . '.buttons.edit', [
                'buttonable' => $buttonable,
                'button' => $button,
              ]),
              'destroy' => route('admin.' . $pluralButtonableRouteName . '.buttons.destroy', [
                'buttonable' => $buttonable,
                'button' => $button,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection