@extends('layouts.admin')

@section('title', 'All Video Categories')

@section('page-title', 'All Video Categories')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'Video Categories',
    'url'  => routeWithCurrentUrlAsRedirect('admin.video-categories.create')
  ])
@endsection

@section('page')
  @if ($video_categories->isNotEmpty())
    <table class="admin-table w-full mt-5">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th class="text-left">Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($video_categories as $videoCategory)
                <tr>
                    <td class="text-center">{{ $videoCategory->id }}</td>
                    <td>{{ $videoCategory->name }}</td>
                    <td>{{ $videoCategory->position }}</td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => routeWithCurrentUrlAsRedirect('admin.video-categories.edit', $videoCategory),
                        'destroy' => routeWithCurrentUrlAsRedirect('admin.video-categories.destroy', $videoCategory)
                      ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


  @endif
@endsection