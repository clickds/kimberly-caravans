@extends('layouts.admin')

@section('title', 'Testimonials')

@section('page-title', 'Testimonials')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.testimonials.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Testimonial</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Testimonials Page'])
@endsection

@section('page')
  @include('admin.testimonials._filter-form')

  @if($testimonials->isNotEmpty())
    <table class="admin-table w-full mt-3">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Sites</td>
        <td>Published</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($testimonials as $testimonial)
        <tr>
          <td>{{ $testimonial->id }}</td>
          <td>{{ $testimonial->name }}</td>
          <td>

            @if($testimonial->sites)
              @foreach($testimonial->sites as $site)
                {{ $site->country }}
                <br>
              @endforeach
            @endif
          </td>
          <td>{{ $testimonial->published_at ? $testimonial->published_at->format('d-m-Y') : '' }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit'    => routeWithCurrentUrlAsRedirect('admin.testimonials.edit', $testimonial),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.testimonials.destroy', $testimonial)
            ])
          </td>
        </tr>
      @endforeach
    </table>
    {{ $testimonials->links() }}
  @endif
@endsection