@extends('layouts.admin')

@section('title', "{$form->name} Submissions")

@section('page-title', "{$form->name} Submissions")

@section('page')
  @if($submissions->isNotEmpty())
    <table class="admin-table">
      <thead>
      <td>ID</td>
      <td>Submission Date</td>
      <td>Actions</td>
      </thead>
      @foreach($submissions as $submission)
        <tr>
          <td>{{ $submission->id }}</td>
          <td>{{ $submission->created_at }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'show'  => route('admin.forms.submissions.show', [
                'form' => $form,
                'submission' => $submission,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection