@extends('layouts/site')

@section('body')
    <h1>{{$location->name}}</h1>

    @if($location->heading_1 and $location->content_1)
        <h2>{{$location->heading_1}}</h2>
        {!! $location->content_1 !!}
    @endif

    @if($location->heading_2 and $location->content_2)
        <h2>{{$location->heading_2}}</h2>
        {!! $location->content_2 !!}
    @endif


@endsection
