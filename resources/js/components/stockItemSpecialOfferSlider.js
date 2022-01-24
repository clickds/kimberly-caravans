import { tns } from "tiny-slider/src/tiny-slider.js";

document.addEventListener("DOMContentLoaded", function() {
    const stockItemSpecialOfferSliders = document.querySelectorAll(
        ".stock-item-special-offer-slider"
    );

    if (stockItemSpecialOfferSliders.length > 0) {
        stockItemSpecialOfferSliders.forEach(slider => {
            const slidesContainer = slider.querySelector(".slides-container");
            const previousButton = slider.querySelector(".control-previous");
            const nextButton = slider.querySelector(".control-next");
            tns({
                container: slidesContainer,
                nav: false,
                items: 1,
                autoplay: true,
                autoplayTimeout: 10000,
                autoplayButtonOutput: false,
                prevButton: previousButton,
                nextButton: nextButton
            });
        });
    }
});
