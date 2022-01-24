@extends('layouts.admin')

@section('title', "{$manufacturer->name} Caravan Ranges")

@section('page-title', "{$manufacturer->name} Caravan Ranges")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.index'),
    'text' => 'Back to manufacturers',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'caravan range',
    'url'  => routeWithCurrentUrlAsRedirect('admin.manufacturers.caravan-ranges.create', $manufacturer)
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Caravan Ranges Page'])
@endsection

@section('page')
  @include('admin.manufacturer.caravan-ranges._filter-form')

  @if ($caravanRanges->isNotEmpty())
    <table class="admin-table w-full">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th class="text-left">Exclusive</th>
          <th>Caravans</th>
          <th>Features</th>
          <th>Specification Small Print</th>
          <th>Feature Images</th>
          <th>Interior Images</th>
          <th>Exterior Images</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($caravanRanges as $caravanRange)
          <tr>
            <td class="text-center">{{ $caravanRange->id }}</td>
            <td>{{ $caravanRange->name }}</td>
            <td class="text-center">{{ $caravanRange->exclusive ? 'Y' : 'N' }}</td>
            <td>
              <a href="{{ route('admin.caravan-ranges.caravans.index', $caravanRange) }}">
                @choice('caravan-ranges.caravans', $caravanRange->caravans_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.caravan-ranges.range-features.index', $caravanRange) }}">
                @choice('global.features', $caravanRange->features_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.caravan-ranges.range-specification-small-prints.index', $caravanRange) }}">
                @choice('global.specification-small-prints', $caravanRange->specification_small_prints_count)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.caravan-ranges.feature-gallery-images.index', $caravanRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              <a href="{{ route('admin.caravan-ranges.interior-gallery-images.index', $caravanRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              <a href="{{ route('admin.caravan-ranges.exterior-gallery-images.index', $caravanRange) }}">
                Manage Images
              </a>
            </td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'edit' => routeWithCurrentUrlAsRedirect('admin.manufacturers.caravan-ranges.edit', [
                  'manufacturer' => $manufacturer,
                  'caravan_range' => $caravanRange,
                ]),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.manufacturers.caravan-ranges.destroy', [
                  'manufacturer' => $manufacturer,
                  'caravan_range' => $caravanRange,
                ]),
                'additional' => [
                  [
                    'text' => trans('global.clone'),
                    'url' => routeWithCurrentUrlAsRedirect('admin.manufacturers.caravan-ranges.clones.create', [
                      'manufacturer' => $manufacturer,
                      'caravan_range' => $caravanRange,
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