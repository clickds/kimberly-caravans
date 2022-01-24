@extends('layouts.admin')

@section('title', "{$caravan->name} Bed Sizes")

@section('page-title', "{$caravan->name} Bed Sizes")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.caravan-ranges.caravans.index', [
      'caravanRange' => $caravan->caravan_range_id,
    ]),
    'text' => 'Back to caravans',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'bed size',
    'url'  => route('admin.caravans.bed-sizes.create', $caravan)
  ])
@endsection

@section('page')
  @if ($bedSizes->isNotEmpty())
    <table class="admin-table w-full mt-5">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Bed Description</th>
                <th class="text-left">Details</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bedSizes as $bedSize)
                <tr>
                    <td class="text-center">{{ $bedSize->id }}</td>
                    <td>{{ $bedSize->bedDescription->name }}</td>
                    <td>{{ $bedSize->details }}</td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => route('admin.caravans.bed-sizes.edit', [
                          'bed_size' => $bedSize,
                          'caravan' => $caravan,
                        ]),
                        'destroy' => route('admin.caravans.bed-sizes.destroy', [
                          'bed_size' => $bedSize,
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