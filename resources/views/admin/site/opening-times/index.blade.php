@extends('layouts.admin')

@section('title', 'Opening Times')

@section('page-title', 'Opening Times')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'opening time',
    'url'  => route('admin.sites.opening-times.create', $site)
  ])
@endsection

@section('page')
  @if($openingTimes->isNotEmpty())
    <table class="admin-table">
        <thead>
            <td>ID</td>
            <td>Day</td>
            <td>Opens At</td>
            <td>Closes At</td>
            <td>Closed</td>
            <td>Actions</td>
        </thead>
        @foreach($openingTimes as $openingTime)
            <tr>
                <td>{{ $openingTime->id }}</td>
                <td>{{ $openingTime->dayName() }}</td>
                <td>
                  {{ $openingTime->opens_at->format('H:i') }}
                </td>
                <td>
                  {{ $openingTime->closes_at->format('H:i') }}
                </td>
                <td>{{ $openingTime->closed ? 'Y' : 'N' }}</td>
                <td>
                  @include('admin._partials.table-row-action-cell', [
                    'edit' => route('admin.sites.opening-times.edit', [
                      'site' => $site,
                      'opening_time' => $openingTime,
                    ]),
                    'destroy' => route('admin.sites.opening-times.destroy', [
                      'site' => $site,
                      'opening_time' => $openingTime,
                    ]),
                  ])
                </td>
            </tr>
        @endforeach
    </table>
  @endif
@endsection