@extends('layouts.admin')

@section('title', "{$caravanRange->name} Exterior Gallery Images")

@section('page-title', "{$caravanRange->name} Exterior Gallery Images")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer_id),
    'text' => 'Back to caravan ranges',
  ])
  @include('admin._partials.button', [
    'url' => route('admin.caravan-ranges.gallery.upload-multiple.create', [
      'caravanRange' => $caravanRange,
      'galleryType' => 'exterior-gallery-images',
    ]),
    'text' => 'Upload Multiple',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'gallery image',
    'url'  => route('admin.caravan-ranges.exterior-gallery-images.create', $caravanRange)
  ])
@endsection

@section('page')
  @if ($galleryImages->isNotEmpty())
    <admin-table
      :headings="['', 'ID', 'Name', 'Actions']"
      :enable-bulk-delete="true"
      bulk-delete-url="{{
        route('admin.caravan-ranges.gallery.bulk-delete', [
          'caravanRange' => $caravanRange,
          'galleryType' => 'exteriorGallery',
        ])
      }}"
      bulk-delete-input-name="gallery_image_ids"
      :record-ids='@json($galleryImages->pluck("id")->toArray())'
    >
      <template #delete-method>
        @method('delete')
      </template>
      <template #csrf>
        @csrf
      </template>
      @foreach($galleryImages as $image)
        <template #row-cells-{{ $image->id }}>
          <td class="text-center">
            <img src="{{ $image->getUrl('thumb') }}" />
          </td>
          <td class="text-center">{{ $image->id }}</td>
          <td>{{ $image->name }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'destroy' => route('admin.caravan-ranges.exterior-gallery-images.destroy', [
                'caravanRange' => $caravanRange,
                'exterior_gallery_image' => $image,
              ])
            ])
          </td>
        </template>
      @endforeach
    </admin-table>
  @endif
@endsection