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
</div>


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

<section class="tab-listing">
    <div class="tab-listing__container">
        <header class="tab-listing__header">
            <div class="tab-listing__title">
                <h2>
                    <span>Discover</span>
                    Our Latest Stock
                </h2>
            </div>
            <div class="tab-listing__tabs">
                <ul class="tab-listing__tabs__list">
                    <li data-id="0" class="tab-listing__tabs__item tab-listing__tabs__item--active">New Caravans</li>
                    <li data-id="1" class="tab-listing__tabs__item">Used Caravans</li>
                    <li data-id="2" class="tab-listing__tabs__item">New Motorhomes</li>
                    <li data-d="3" class="tab-listing__tabs__item">Used Motorhomes</li>
                </ul>
            </div>
        </header>
        <div class="tab-listing__list tab-listing__list--active" id="tab-listing__list-0">
            <!-- THIS IS THE NEW CARAVANS TAB CONTENT -->
            @foreach($new_caravans as $caravan)
                @include('caravans/listing-item')
            @endforeach
            </div>
        <!-- END OF NEW CARAVANS -->

    <div class="tab-listing__list" id="tab-listing__list-1">
        <!-- THIS IS THE USED CARAVANS TAB CONTENT -->
        @foreach($used_caravans as $caravan)
            @include('caravans/listing-item')
            <!-- there is no monthly payment amount in the api data, nor is there a town/location -->
        @endforeach
        <!-- END OF USED CARAVANS -->
    </div> 

    <div class="tab-listing__list" id="tab-listing__list-2">
    <!-- THIS IS THE NEW MOTORHOMES TAB CONTENT -->
    @foreach($new_motor_homes as $caravan)
     @include('caravans/listing-item')
    <!-- there is no monthly payment amount in the api data, nor is there a town/location -->
    @endforeach
    <!-- END OF NEW MOTORHOMES -->
    </div>

    <div class="tab-listing__list" id="tab-listing__list-3">
    <!-- THIS IS THE USED MOTORHOMES TAB CONTENT -->
    @foreach($used_motor_homes as $caravan)
        @include('caravans/listing-item')
        <!-- there is no monthly payment amount in the api data, nor is there a town/location -->
    @endforeach
    <!-- END OF USED MOTORHOMES -->
    </div>
</div>
</section>

@include('home/call-to-action')
@include('home/latest-news')

@endsection
   