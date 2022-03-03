@extends('layouts/site')

@section('body')
@include('components/page-banner')
@include('components/listing-information')

<section class="awnings-list">
    <div class="awnings-list__inner">
        <h2 class="awnings-list__title">Caravan Awnings</h2>
        <div class="awnings-list__list">
            <div class="awnings-list__list__item">
                <a class="awnings-list__list__link" href="#">
                    <div class="awnings-list__list__item__inner">
                        <div class="awnings-list__list__item__title">
                            Full size inflatable awning
                        </div>
                        <div class="awnings-list__list__item__image">
                            <img src="https://via.placeholder.com/536x308" alt="">
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
