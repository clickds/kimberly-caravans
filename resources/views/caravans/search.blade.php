@extends('layouts/site')

@section('body')
    @if ($caravans)
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
    @else
        <div class="alert alert-warning">
            No caravans available
        </div>
    @endif
@endsection
