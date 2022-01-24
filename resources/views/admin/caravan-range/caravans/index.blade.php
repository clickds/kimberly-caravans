@extends('layouts.admin')

@section('title', "{$caravanRange->name} Caravans")

@section('page-title', "{$caravanRange->name} Caravans")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer_id),
    'text' => 'Back to caravan ranges',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'caravan',
    'url'  => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.caravans.create', $caravanRange)
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Caravan Range Page'])
@endsection

@section('page')
  @include('admin.caravan-range.caravans._filter-form')

  @if ($caravans->isNotEmpty())
    <table class="admin-table w-full mt-5">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th class="text-left">Layout</th>
                <th class="text-left">Exclusive</th>
                <th class="text-left">Live</th>
                <th class="text-left">Bed Sizes</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($caravans as $caravan)
                <tr>
                    <td class="text-center">{{ $caravan->id }}</td>
                    <td>{{ $caravan->name }}</td>
                    <td>{{ $caravan->layout->name }}</td>
                    <td>{{ $caravan->exclusive ? 'Y' : 'N' }}</td>
                    <td>{{ $caravan->live ? 'Y' : 'N' }}</td>
                    <td>
                      <a href="{{ route('admin.caravans.bed-sizes.index', $caravan) }}">
                        @choice('global.bed_sizes', $caravan->bed_sizes_count)
                      </a>
                    </td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.caravans.edit', [
                          'caravanRange' => $caravanRange,
                          'caravan' => $caravan,
                        ]),
                        'destroy' => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.caravans.destroy', [
                          'caravanRange' => $caravanRange,
                          'caravan' => $caravan,
                        ])
                      ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
  @endif
@endsection