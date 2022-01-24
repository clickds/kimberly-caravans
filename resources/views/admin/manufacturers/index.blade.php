@extends('layouts.admin')

@section('title', 'All Manufacturers')

@section('page-title', 'All Manufacturers')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'manufacturer',
    'url'  => routeWithCurrentUrlAsRedirect('admin.manufacturers.create')
  ])
@endsection

@section('page')
  @include('admin.manufacturers._filter-form')

  @if ($manufacturers->isNotEmpty())
    <table class="admin-table w-full mt-5 overflow-x-auto">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th>Exclusive</th>
                <th>Motorhome Ranges</th>
                <th>Motorhome Position</th>
                <th>Caravan Ranges</th>
                <th>Caravan Position</th>
                <th>Caravan Stock Items</th>
                <th>Motorhome Stock Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manufacturers as $manufacturer)
                <tr>
                    <td class="text-center">{{ $manufacturer->id }}</td>
                    <td>{{ $manufacturer->name }}</td>
                    <td>{{ $manufacturer->exclusive ? 'Y' : 'N' }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.manufacturers.motorhome-ranges.index', $manufacturer) }}">
                        @choice('manufacturers.motorhome-ranges', $manufacturer->motorhome_ranges_count)
                      </a>
                    </td>
                    <td class="text-center">{{ $manufacturer->motorhome_position }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.manufacturers.caravan-ranges.index', $manufacturer) }}">
                        @choice('manufacturers.caravan-ranges', $manufacturer->caravan_ranges_count)
                      </a>
                    </td>
                    <td class="text-center">{{ $manufacturer->caravan_position }}</td>
                    <td>
                      {{ $manufacturer->caravan_stock_items_count }}
                    </td>
                    <td>
                      {{ $manufacturer->motorhome_stock_items_count }}
                    </td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => routeWithCurrentUrlAsRedirect('admin.manufacturers.edit', $manufacturer),
                        'destroy' => routeWithCurrentUrlAsRedirect('admin.manufacturers.destroy', $manufacturer)
                      ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $manufacturers->links() }}

  @endif
@endsection