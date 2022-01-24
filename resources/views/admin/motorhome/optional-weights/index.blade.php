@extends('layouts.admin')

@section('title', "{$motorhome->name} Optional Weights")

@section('page-title', "{$motorhome->name} Optional Weights")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.motorhome-ranges.motorhomes.index', [
      'motorhomeRange' => $motorhome->motorhome_range_id,
    ]),
    'text' => 'Back to motorhomes',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'optional weight',
    'url'  => route('admin.motorhomes.optional-weights.create', $motorhome)
  ])
@endsection

@section('page')
  @if ($optionalWeights->isNotEmpty())
    <table class="admin-table w-full mt-5">
        <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th class="text-left">Value</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($optionalWeights as $optionalWeight)
                <tr>
                    <td class="text-center">{{ $optionalWeight->id }}</td>
                    <td>{{ $optionalWeight->name }}</td>
                    <td>{!! $optionalWeight->value !!}</td>
                    <td>
                      @include('admin._partials.table-row-action-cell', [
                        'edit' => route('admin.motorhomes.optional-weights.edit', [
                          'optional_weight' => $optionalWeight,
                          'motorhome' => $motorhome,
                        ]),
                        'destroy' => route('admin.motorhomes.optional-weights.destroy', [
                          'optional_weight' => $optionalWeight,
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