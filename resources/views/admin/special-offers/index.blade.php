@extends('layouts.admin')

@section('title', 'Special Offers')

@section('page-title', 'Special Offers')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'special offer',
    'url'  => routeWithCurrentUrlAsRedirect('admin.special-offers.create')
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Special Offers Page'])
@endsection

@section('page')
  @include('admin.special-offers._filter-form')

  @if($specialOffers->isNotEmpty())
    <table class="admin-table">
        <thead>
            <td>ID</td>
            <td>Name</td>
            <td>Type</td>
            <td>Landscape Image</td>
            <td>Square Image</td>
            <td>Links Caravans</td>
            <td>Links Motorhomes</td>
            <td>Links Managers Specials</td>
            <td>Links On Sale</td>
            <td>Position</td>
            <td>Actions</td>
        </thead>
        @foreach($specialOffers as $specialOffer)
            <tr>
                <td>{{ $specialOffer->id }}</td>
                <td>{{ $specialOffer->name }}</td>
                <td>{{ $specialOffer->offer_type }}</td>
                <td>
                  @if ($url = $specialOffer->getFirstMediaUrl('landscapeImage', 'thumb'))
                    <img src="{{ $url }}" />
                  @endif
                </td>
                <td>
                  @if ($url = $specialOffer->getFirstMediaUrl('squareImage', 'thumb'))
                    <img src="{{ $url }}" />
                  @endif
                </td>
                <td>
                  {{ $specialOffer->link_used_caravan_stock ? 'Y' : 'N' }}
                </td>
                <td>
                  {{ $specialOffer->link_used_motorhome_stock ? 'Y' : 'N' }}
                </td>
                <td>
                  {{ $specialOffer->link_managers_special_stock ? 'Y' : 'N' }}
                </td>
                <td>
                  {{ $specialOffer->link_on_sale_stock ? 'Y' : 'N' }}
                </td>
                <td>{{ $specialOffer->position }}</td>
                <td>
                  @include('admin._partials.table-row-action-cell', [
                    'edit' => routeWithCurrentUrlAsRedirect('admin.special-offers.edit', $specialOffer),
                    'destroy' => routeWithCurrentUrlAsRedirect('admin.special-offers.destroy', $specialOffer),
                  ])
                </td>
            </tr>
        @endforeach

        {{ $specialOffers->links() }}
    </table>
  @endif
@endsection