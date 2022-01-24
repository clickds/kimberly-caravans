@extends('layouts.admin')

@section('title', 'Logos')

@section('page-title', 'Logos')

@section('page-actions')
  @include('admin._partials.button', [
    'text' => 'New Logo',
    'url' => route('admin.logos.create')
  ])
@endsection

@section('page')
  @if($logos->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>External URL</td>
        <td>Page</td>
        <td>Display Location</td>
        <td>Actions</td>
      </thead>
      @foreach($logos as $logo)
        <tr>
          <td>{{ $logo->id }}</td>
          <td>{{ $logo->name }}</td>
          <td>
            @if($logo->external_url)
              <a href="{{ $logo->external_url }}" target="_blank">{{ $logo->external_url }}</a>
            @else
              None
            @endif
          <td>
            @if($logo->page)
              <a href="{{ pageLink($logo->page) }}" target="_blank">{{ $logo->page->name }}</a>
            @else
              None
            @endif
          </td>
          <td>{{ $logo->display_location }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => route('admin.logos.edit', [
                'logo' => $logo,
              ]),
              'destroy' => route('admin.logos.destroy', [
                'logo' => $logo,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection