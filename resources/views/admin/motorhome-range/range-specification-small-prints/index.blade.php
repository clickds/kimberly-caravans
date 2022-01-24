@extends('layouts.admin')

@section('title', "{$motorhomeRange->name} Specification Small Prints")

@section('page-title', "{$motorhomeRange->name} Specification Small Prints")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer_id),
    'text' => 'Back to motorhome ranges',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'specification small print',
    'url'  => route('admin.motorhome-ranges.range-specification-small-prints.create', $motorhomeRange)
  ])
@endsection

@section('page')
  @if ($rangeSpecificationSmallPrints->isNotEmpty())
    <table class="admin-table table-auto">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th>Content</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($rangeSpecificationSmallPrints as $smallPrint)
          <tr>
            <td class="text-center">{{ $smallPrint->id }}</td>
            <td>{{ $smallPrint->name }}</td>
            <td>{!! $smallPrint->content !!}</td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'additional' => [
                  [
                    'text' => trans('global.clone'),
                    'url' => route('admin.range-specification-small-prints.clones.create', $smallPrint),
                  ],
                ],
                'edit' => route('admin.motorhome-ranges.range-specification-small-prints.edit', [
                  'motorhomeRange' => $motorhomeRange,
                  'range_specification_small_print' => $smallPrint,
                ]),
                'destroy' => route('admin.motorhome-ranges.range-specification-small-prints.destroy', [
                  'motorhomeRange' => $motorhomeRange,
                  'range_specification_small_print' => $smallPrint,
                ])
              ])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection