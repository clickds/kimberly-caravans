import { tns } from "tiny-slider/src/tiny-slider.js";
document.addEventListener("DOMContentLoaded", function() {
    const vehicleRangeSlider = document.querySelector(".vehicle-range-slider");

    if (vehicleRangeSlider) {
        const controlsContainer = vehicleRangeSlider.querySelector(".controls");
        const slidesContainer = vehicleRangeSlider.querySelector(
            ".slides-container"
        );
        tns({
            container: slidesContainer,
            controlsContainer: controlsContainer,
            items: 1,
            autoplay: false,
            controls: true,
            responsive: {
                768: {
                    items: 2
                },
                1024: {
                    items: 4
                }
            }
        });
    }
});
