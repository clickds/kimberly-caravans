import { tns } from "tiny-slider/src/tiny-slider.js";

document.addEventListener("DOMContentLoaded", function() {
    const specialOfferSliders = document.querySelectorAll(
        ".special-offer-slider"
    );

    if (specialOfferSliders.length > 0) {
        specialOfferSliders.forEach(slider => {
            const controlsContainer = slider.querySelector(".controls");
            const navigationContainer = slider.querySelector(".navigation");
            const slidesContainer = slider.querySelector(".slides-container");
            tns({
                container: slidesContainer,
                controlsContainer: controlsContainer,
                navContainer: navigationContainer,
                items: 1,
                autoplay: true,
                autoplayButtonOutput: false
            });
        });
    }
});
