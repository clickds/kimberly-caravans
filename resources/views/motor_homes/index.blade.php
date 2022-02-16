@extends('layouts/site')

@section('body')
    @foreach($new_motor_homes as $caravan) :
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

    @foreach($used_motor_homes as $caravan) :
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
