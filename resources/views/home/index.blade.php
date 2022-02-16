@extends('layouts/site')

@section('body')
    <!-- THIS IS THE NEW CARAVANS TAB CONTENT -->
    @foreach($new_caravans as $caravan) :
    {{$caravan->id}}
        {{ $caravan->make }}
        {{ $caravan->model }}
        {{ $caravan->year }}
    {{$caravan->type->name}}
        {{ $caravan->reg }}
        {{ $caravan->berths }} Berths
        {{ $caravan->web_price }}
        {{ $caravan->previous_price }}

        <!-- there is no monthly payment amount in the api data, nor is there a town/location -->

    @endforeach
    <!-- END OF NEW CARAVANS -->

    <!-- THIS IS THE USED CARAVANS TAB CONTENT -->
    @foreach($used_caravans as $caravan) :
    {{ $caravan->make }}
    {{ $caravan->model }}
    {{ $caravan->year }}
    {{$caravan->type->name}}
    {{ $caravan->reg }}
    {{ $caravan->berths }} Berths
    {{ $caravan->web_price }}
    {{ $caravan->previous_price }}

    <!-- there is no monthly payment amount in the api data, nor is there a town/location -->

    @endforeach
    <!-- END OF USED CARAVANS -->


    <!-- THIS IS THE NEW MOTORHOMES TAB CONTENT -->
    @foreach($new_motor_homes as $motorHome) :
    {{ $motorHome->make }}
    {{ $motorHome->model }}
    {{ $motorHome->year }}
    {{$motorHome->type->name}}
    {{ $motorHome->reg }}
    {{ $motorHome->berths }} Berths
    {{ $motorHome->web_price }}
    {{ $motorHome->previous_price }}

    <!-- there is no monthly payment amount in the api data, nor is there a town/location -->

    @endforeach
    <!-- END OF NEW MOTORHOMES -->


    <!-- THIS IS THE USED MOTORHOMES TAB CONTENT -->
    @foreach($used_motor_homes as $motorHome) :
    {{ $motorHome->make }}
    {{ $motorHome->model }}
    {{ $motorHome->year }}
    {{$motorHome->type->name}}
    {{ $motorHome->reg }}
    {{ $motorHome->berths }} Berths
    {{ $motorHome->web_price }}
    {{ $motorHome->previous_price }}

    <!-- there is no monthly payment amount in the api data, nor is there a town/location -->

    @endforeach
    <!-- END OF USED MOTORHOMES -->
@endsection
