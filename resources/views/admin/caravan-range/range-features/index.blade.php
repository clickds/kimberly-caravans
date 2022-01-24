@extends('layouts.admin')

@section('title', "{$caravanRange->name} Features")

@section('page-title', "{$caravanRange->name} Features")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer_id),
    'text' => 'Back to caravan ranges',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'feature',
    'url'  => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.range-features.create', $caravanRange)
  ])
@endsection

@section('page')
  @include('admin.caravan-range.range-features._filter-form')

  @if ($rangeFeatures->isNotEmpty())
    <table class="admin-table table-auto">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th class="text-left">Key Feature</th>
          <th class="text-left">Warranty</th>
          <th>Clone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($rangeFeatures as $rangeFeature)
          <tr>
            <td class="text-center">{{ $rangeFeature->id }}</td>
            <td>{{ $rangeFeature->name }}</td>
            <td>{{ $rangeFeature->key ? 'Y' : 'N' }}</td>
            <td>{{ $rangeFeature->warranty ? 'Y' : 'N' }}</td>
            <td>
              <form action="{{ route('admin.range-features.clones.store', $rangeFeature) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full inline-block ml-1">
                  Clone
                </button>
              </form>
            </td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'edit' => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.range-features.edit', [
                  'caravanRange' => $caravanRange,
                  'range_feature' => $rangeFeature,
                ]),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.caravan-ranges.range-features.destroy', [
                  'caravanRange' => $caravanRange,
                  'range_feature' => $rangeFeature,
                ])
              ])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection