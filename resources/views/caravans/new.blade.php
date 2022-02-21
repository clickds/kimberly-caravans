@extends('layouts/site')

@section('body')
    @include('components/page-banner')
        @include('caravans/listing-start')
        @foreach($caravans as $caravan)
            @include('caravans/listing-item')
        @endforeach
        @include('caravans/listing-end')    
@endsection
