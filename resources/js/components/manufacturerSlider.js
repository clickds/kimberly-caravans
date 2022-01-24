import { tns } from "tiny-slider/src/tiny-slider.js";
document.addEventListener("DOMContentLoaded", function() {
    const manufacturerSliders = document.querySelectorAll(
        ".manufacturer-slider-container .slider-container"
    );

    if (manufacturerSliders.length > 0) {
        manufacturerSliders.forEach(slider => {
            const slidesContainer = slider.querySelector(
                ".manufacturer-slider"
            );
            const controlsContainer = slider.querySelector(".controls");
            const numberOfSlides = slider.querySelectorAll(".slide").length;
            tns({
                container: slidesContainer,
                autoplay: true,
                autoplayButtonOutput: false,
                autoplayHoverPause: true,
                controlsContainer: controlsContainer,
                center: true,
                nav: false,
                items: 1,
                responsive: {
                    768: {
                        items: 3,
                        disable: numberOfSlides < 3
                    },
                    1024: {
                        items: 4,
                        disable: numberOfSlides < 4
                    },
                    1280: {
                        items: 6,
                        disable: numberOfSlides < 6
                    }
                }
            });
        });
    }
});
