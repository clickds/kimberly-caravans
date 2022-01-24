import { tns } from "tiny-slider/src/tiny-slider.js";

class SyncedSlider {
    constructor(options) {
        const container = options.sliderContainer;
        const mainSliderContainer = container.querySelector(
            options.mainSliderContainer
        );
        const navSliderContainer = container.querySelector(
            options.navSliderContainer
        );
        const previousButton = container.querySelector(".control-previous");
        const nextButton = container.querySelector(".control-next");

        this.slider = tns({
            container: mainSliderContainer,
            items: 1,
            autoHeight: true,
            lazyload: false,
            prevButton: previousButton,
            nextButton: nextButton,
            slideBy: "page",
            loop: true,
            navContainer: navSliderContainer
        });

        this.thumbnails = tns({
            container: navSliderContainer,
            items: 3,
            gutter: 5,
            lazyload: false,
            prevButton: previousButton,
            nextButton: nextButton,
            loop: false,
            slideBy: 1,
            nav: false,
            responsive: {
                "768": {
                    items: 5
                }
            }
        });

        this.slider.events.on("indexChanged", evt =>
            this.nextThumbnailSlide(evt)
        );
    }

    nextThumbnailSlide(evt) {
        // hack beacause of strange indexes from tns slider
        const indexToGoTo =
            evt.index > this.slider.getInfo().slideCount ? 0 : evt.index - 1;

        this.thumbnails.goTo(indexToGoTo);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const stockItemSlider = document.querySelector(".synced-slider");

    if (stockItemSlider) {
        new SyncedSlider({
            sliderContainer: stockItemSlider,
            mainSliderContainer: ".main-slider",
            navSliderContainer: ".nav-slider"
        });
    }
});
