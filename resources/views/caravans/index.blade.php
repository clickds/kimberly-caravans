@extends('layouts/site')

@section('body')
    <!-- THIS IS THE NEW CARAVANS TAB CONTENT -->
    @include('components/page-banner')
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
@endsection
