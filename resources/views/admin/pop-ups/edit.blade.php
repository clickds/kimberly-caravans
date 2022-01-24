@extends('layouts.admin')

@section('title', 'Edit Pop Up')

@section('page-title', 'Edit Pop Up')

@section('page')
  <div class='w-full'>
    <pop-up csrf-token='{{ csrf_token() }}'
      :errors='@json($errors->getMessages(), JSON_FORCE_OBJECT)'
      :exists='true'
      :initial-appears-on-page-ids='@json(old('appears_on_page_ids', $appearsOnPageIds))'
      :initial-appears-on-all-pages='@json((bool) old('appears_on_all_pages', $popUp->appears_on_all_pages))'
      :initial-appears-on-new-motorhome-pages='@json((bool) old('appears_on_new_motorhome_pages', $popUp->appears_on_new_motorhome_pages))'
      :initial-appears-on-new-caravan-pages='@json((bool) old('appears_on_new_caravan_pages', $popUp->appears_on_new_caravan_pages))'
      :initial-appears-on-used-motorhome-pages='@json((bool) old('appears_on_used_motorhome_pages', $popUp->appears_on_used_motorhome_pages))'
      :initial-appears-on-used-caravan-pages='@json((bool) old('appears_on_used_caravan_pages', $popUp->appears_on_used_caravan_pages))'
      :initial-caravan-range-ids='@json(old('caravan_range_ids', $caravanRangeIds))'
      :initial-motorhome-range-ids='@json(old('motorhome_range_ids', $caravanRangeIds))'
      :caravan-ranges='@json($caravanRanges->map->getWrappedObject())'
      :motorhome-ranges='@json($motorhomeRanges->map->getWrappedObject())'
      initial-expired-at='{{ old('expired_at', $popUp->getExpiredAtAsIso8601String()) }}'
      initial-external-url='{{ old('external_url', $popUp->external_url) }}'
      :initial-live='@json(old('live', $popUp->live) ? true : false)'
      initial-name='{{ old('name', $popUp->name) }}'
      :initial-page-id='@json(old('page_id', $popUp->page_id))'
      initial-published-at='{{ old('published_at', $popUp->getPublishedAtAsIso8601String()) }}'
      :initial-site-id='@json(old('site_id', $popUp->site_id))'
      mobile-thumb-url='{{ $popUp->getFirstMediaUrl('mobileImage', 'thumb') }}'
      desktop-thumb-url='{{ $popUp->getFirstMediaUrl('desktopImage', 'thumb') }}'
      :sites='@json($sites)'
      url='{{ route('admin.pop-ups.update', $popUp) }}'></pop-up>
  </div>
@endsection