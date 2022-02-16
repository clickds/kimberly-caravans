@extends('layouts/site')

@section('body')

<div class="carousel">
    <div class="carousel__inner">
          <h1 class="carousel__title">The Caravan and <br />Motorhome <br />People</h1>
          <p class="carousel__text" class="text-xl text-white mb-10 leading-none">We have a large selection available for you</p>
          <div class="carousel__row">
              <a class="carousel__row__button" href="#">View Motorhomes</a>
              <a class="carousel__row__button" href="#">View Caravans</a>
          </div>
    </div>
    <div class="carousel__background"></div>
</div


<section class="site-search">
    <div class="container site-search__container">
        <form class="site-search__form">
            <div class="site-search__form__inner">
            <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <select name="type" class="">
                        <option value="" selected disabled>Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                        <option value="">Type</option>
                    </select>
                </div>
                <div class="site-search__form__item">
                    <button type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</section>



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
