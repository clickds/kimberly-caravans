@extends('layouts/site')

@section('body')
    @include('components/page-banner')
    @foreach($caravans as $caravan) :
    {{$caravan->id}}
    {{ $caravan->make }}
    {{ $caravan->model }}
    {{ $caravan->year }}
    {{$caravan->type->name}}
    {{ $caravan->reg }}
    {{ $caravan->berths }} Berths
    {{ $caravan->web_price }}
    {{ $caravan->previous_price }}
    @endforeach
@endsection
