import { tns } from "tiny-slider/src/tiny-slider.js";
document.addEventListener("DOMContentLoaded", function() {
    const homepageCtasContainer = document.querySelector(
        ".homepage__ctas.mobile"
    );

    if (homepageCtasContainer) {
        const sliderContainer = homepageCtasContainer.querySelector(
            ".slider-container"
        );
        const controlsContainer = homepageCtasContainer.querySelector(
            ".controls"
        );
        tns({
            container: sliderContainer,
            items: 1,
            autoplay: true,
            autoplayButtonOutput: false,
            autoplayHoverPause: true,
            controlsContainer: controlsContainer,
            nav: false
        });
    }
});
