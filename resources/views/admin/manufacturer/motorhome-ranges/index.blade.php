@extends('layouts.admin')

@section('title', "{$manufacturer->name} Motorhome Ranges")

@section('page-title', "{$manufacturer->name} Motorhome Ranges")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.index'),
    'text' => 'Back to manufacturers',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'motorhome range',
    'url'  => routeWithCurrentUrlAsRedirect('admin.manufacturers.motorhome-ranges.create', $manufacturer)
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Motorhome Ranges Page'])
@endsection

@section('page')
  @include('admin.manufacturer.motorhome-ranges._filter-form')

  @if ($motorhomeRanges->isNotEmpty())
    <table class="admin-table table-auto">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th class="text-left">Exclusive</th>
          <th>Motorhomes</th>
          <th>Features</th>
          <th>Specification Small Print</th>
          <th>Feature Images</th>
          <th>Interior Images</th>
          <th>Exterior Images</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($motorhomeRanges as $motorhomeRange)
          <tr>
            <td class="text-center">{{ $motorhomeRange->id }}</td>
            <td>{{ $motorhomeRange->name }}</td>
            <td class="text-center">{{ $motorhomeRange->exclusive ? 'Y' : 'N' }}</td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange) }}">
                @choice('motorhome-ranges.motorhomes', $motorhomeRange->motorhomes_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.range-features.index', $motorhomeRange) }}">
                @choice('global.features', $motorhomeRange->features_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.range-specification-small-prints.index', $motorhomeRange) }}">
                @choice('global.specification-small-prints', $motorhomeRange->specification_small_prints_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.feature-gallery-images.index', $motorhomeRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.interior-gallery-images.index', $motorhomeRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              <a href="{{ route('admin.motorhome-ranges.exterior-gallery-images.index', $motorhomeRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'edit' => routeWithCurrentUrlAsRedirect('admin.manufacturers.motorhome-ranges.edit', [
                  'manufacturer' => $manufacturer,
                  'motorhome_range' => $motorhomeRange,
                ]),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.manufacturers.motorhome-ranges.destroy', [
                  'manufacturer' => $manufacturer,
                  'motorhome_range' => $motorhomeRange,
                ]),
                'additional' => [
                  [
                    'text' => trans('global.clone'),
                    'url' => routeWithCurrentUrlAsRedirect('admin.manufacturers.motorhome-ranges.clones.create', [
                      'manufacturer' => $manufacturer,
                      'motorhome_range' => $motorhomeRange,
                    ]),
                  ],
                ],
              ])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection