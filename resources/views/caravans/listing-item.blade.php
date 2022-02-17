 <div class="tab-listing__list__item" data-id="{{$caravan->id}}">
    <div class="tab-listing__list__item__inner">
        <div class="tab-listing__list__item__gallery">
            <div class="tab-listing__list__item__gallery__main">
                <img src="https://via.placeholder.com/300x200" alt="">
            </div>
            <div class="tab-listing__list__item__gallery__thumb">
                <img src="https://via.placeholder.com/300x200" alt="">
                <img src="https://via.placeholder.com/300x200" alt="">
            </div>
        </div>
        <div class="tab-listing__list__item__details">
            <div class="tab-listing__list__item__attr">
            <div class="tab-listing__list__item__attr__brand">
                {{ $caravan->make }}
            </div>
            <div class="tab-listing__list__item__attr__name">
                {{ $caravan->model }}
            </div>
            <div class="tab-listing__list__item__attr__info">
                {{ $caravan->year }} | {{$caravan->type->name}} | {{ $caravan->berths }} Berths | {{ $caravan->reg }}
            </div>
            <div class="tab-listing__list__item__attr__location">
                Nottingham
            </div>
        </div>
        <div class="tab-listing__list__item__pricing">
            <div class="tab-listing__list__item__price">
                <!-- <span>Now</span> --> £{{ $caravan->web_price }}
            </div>
            @if($caravan->previous_price !== "0.00")
                <div class="tab-listing__list__item__price tab-listing__list__item__price--old">
                    <span>Was</span> £{{ $caravan->previous_price }}
                </div>
            @endif
            <div class="tab-listing__list__item__finance">
                £995 Monthly
            </div>
            <a class="tab-listing__list__item__compare" href="#{{$caravan->id}}">
                Add to compare</a>
        </div>
    </div>
</div>
</div>
