@extends('layouts.admin')

@section('title', 'Call To Actions')

@section('page-title', 'Call To Actions')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.ctas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Call To Action</a>
@endsection

@section('page')
  @include('admin.ctas._filter-form')

  @if($ctas->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>Title</td>
        <td>Type</td>
        <td>Site</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($ctas as $cta)
        <tr>
          <td>{{ $cta->title }}</td>
          <td>{{ $cta->type }}</td>
          <td>
            {{ $cta->site->country }}
          </td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.ctas.edit', $cta),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.ctas.destroy', $cta)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $ctas->links() }}

  @endif
@endsection