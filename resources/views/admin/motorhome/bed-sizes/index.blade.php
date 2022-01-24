@extends('layouts.admin')

@section('title', "{$motorhome->name} Bed Sizes")

@section('page-title', "{$motorhome->name} Bed Sizes")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.motorhome-ranges.motorhomes.index', [
      'motorhomeRange' => $motorhome->motorhome_range_id,
    ]),
    'text' => 'Back to motorhomes',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'bed size',
    'url'  => route('admin.motorhomes.bed-sizes.create', $motorhome)
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
                        'edit' => route('admin.motorhomes.bed-sizes.edit', [
                          'bed_size' => $bedSize,
                          'motorhome' => $motorhome,
                        ]),
                        'destroy' => route('admin.motorhomes.bed-sizes.destroy', [
                          'bed_size' => $bedSize,
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