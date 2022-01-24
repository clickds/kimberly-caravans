@extends('layouts.admin')

@section('title', "{$motorhomeRange->name} Motorhomes")

@section('page-title', "{$motorhomeRange->name} Motorhomes")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => routeWithCurrentUrlAsRedirect('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer_id),
    'text' => 'Back to motorhome ranges',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'motorhome',
    'url'  => routeWithCurrentUrlAsRedirect('admin.motorhome-ranges.motorhomes.create', $motorhomeRange)
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Motorhome Range Page'])
@endsection

@section('page')
  @include('admin.motorhome-range.motorhomes._filter-form')

  @if ($motorhomes->isNotEmpty())
    <table class="admin-table w-full mt-5">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th>Bed Sizes</th>
                <th>Optional Weights</th>
                <th>Layout</th>
                <th>Exclusive</th>
                <th>Live</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($motorhomes as $motorhome)
                <tr>
                    <td class="text-center">{{ $motorhome->id }}</td>
                    <td>{{ $motorhome->name }}</td>
                    <td>
                      <a href="{{ route('admin.motorhomes.bed-sizes.index', $motorhome) }}">
                        @choice('global.bed_sizes', $motorhome->bed_sizes_count)
                      </a>
                    </td>
                    <td>
                      <a href="{{ route('admin.motorhomes.optional-weights.index', $motorhome) }}">
                        @choice('global.optional_weights', $motorhome->optional_weights_count)
                      </a>
                    </td>
                    <td>{{ $motorhome->layout->name }}</td>
                    <td>{{ $motorhome->exclusive ? 'Y' : 'N' }}</td>
                    <td>{{ $motorhome->live ? 'Y' : 'N' }}</td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => routeWithCurrentUrlAsRedirect('admin.motorhome-ranges.motorhomes.edit', [
                          'motorhomeRange' => $motorhomeRange,
                          'motorhome' => $motorhome,
                        ]),
                        'destroy' => routeWithCurrentUrlAsRedirect('admin.motorhome-ranges.motorhomes.destroy', [
                          'motorhomeRange' => $motorhomeRange,
                          'motorhome' => $motorhome,
                        ])
                      ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
  @endif
@endsection