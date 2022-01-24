@extends('layouts.admin')

@section('title', "{$motorhomeRange->name} Exterior Gallery Images")

@section('page-title', "{$motorhomeRange->name} Exterior Gallery Images")

@section('page-actions')
  @include('admin._partials.button', [
    'url' => route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer_id),
    'text' => 'Back to motorhome ranges',
  ])
  @include('admin._partials.button', [
    'url' => route('admin.motorhome-ranges.gallery.upload-multiple.create', [
      'motorhomeRange' => $motorhomeRange,
      'galleryType' => 'exterior-gallery-images',
    ]),
    'text' => 'Upload Multiple',
  ])
  @include('admin._partials.page-actions', [
    'name' => 'gallery image',
    'url'  => route('admin.motorhome-ranges.exterior-gallery-images.create', $motorhomeRange)
  ])
@endsection

@section('page')
  @if ($galleryImages->isNotEmpty())
    <admin-table
      :headings="['', 'ID', 'Name', 'Actions']"
      :enable-bulk-delete="true"
      bulk-delete-url="{{
        route('admin.motorhome-ranges.gallery.bulk-delete', [
          'motorhomeRange' => $motorhomeRange,
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
              'destroy' => route('admin.motorhome-ranges.exterior-gallery-images.destroy', [
                'motorhomeRange' => $motorhomeRange,
                'exterior_gallery_image' => $image,
              ])
            ])
          </td>
        </template>
      @endforeach
    </admin-table>
  @endif
@endsection