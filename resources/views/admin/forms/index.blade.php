@extends('layouts.admin')

@section('title', 'Forms')

@section('page-title', 'Forms')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.forms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Form
  </a>
@endsection

@section('page')
  @include('admin.forms._filter-form')

  @if($forms->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Type</td>
        <td>Main Recipient</td>
        <td>Pages</td>
        <td>Fieldsets</td>
        <td>Submissions</td>
        <td>Clone</td>
        <td>Actions</td>
      </thead>
      @foreach($forms as $form)
        <tr>
          <td>{{ $form->id }}</td>
          <td>
            {{ $form->name }}
          </td>
          <td>{{ $form->type }}</td>
          <td>{{ $form->email_to }}</td>
          <td>
            <ul class="list-disc">
              @foreach ($form->areas as $area)
                <li>
                  @if ($page = $area->page)
                    <a href="{{ pageLink($page) }}" target="_blank" rel="noopener noreferrer">
                      {{ $page->name }}
                    </a>
                  @endif
                </li>
              @endforeach
            </ul>
          </td>
          <td>
            <a href="{{ route('admin.fieldsets.index') }}">
              @choice('forms.fieldsets', $form->fieldsets->count())
            </a>
            <ul>
              @foreach ($form->fieldsets as $fieldset)
                <li>{{ $fieldset->name }}</li>
              @endforeach
            </ul>
          </td>
          <td>
            <a href="{{ route('admin.forms.submissions.index', $form) }}">
              @choice('forms.submissions', $form->submissions_count)
            </a>
          </td>
          <td>
            <div>
              <form action="{{ route('admin.forms.clones.store', $form) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-greeen-700 text-white py-2 px-4 rounded-full inline-block ml-1">
                  Clone
                </button>
              </form>
            </div>
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.forms.edit', $form),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.forms.destroy', $form),
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $forms->links() }}
  @endif
@endsection