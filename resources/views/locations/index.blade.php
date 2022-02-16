@extends('layouts/site')

@section('body')
    @foreach($locations as $location)
    {{$location->name}}
        {{$location->address}}
        {{$location->contact_number}}
        {{$location->lng}}
        {{$location->lat}}
        <a href="{{ route() }}"
    @endforeach
@endsection
