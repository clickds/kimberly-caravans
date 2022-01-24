import { tns } from "tiny-slider/src/tiny-slider.js";
document.addEventListener("DOMContentLoaded", function() {
    const imageBannerSlider = document.querySelector(".image-banner-slider");

    if (imageBannerSlider) {
        tns({
            container: imageBannerSlider,
            items: 1,
            autoplay: true,
            autoplayButtonOutput: false,
            autoplayHoverPause: true,
            controls: false,
            //center: true,
            //gutter: 20,
            nav: false
        });
    }
});
