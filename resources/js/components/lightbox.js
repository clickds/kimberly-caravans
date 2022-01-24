import GLightbox from "glightbox";

document.addEventListener("DOMContentLoaded", function() {
    const lightbox = GLightbox({
        selector: ".lightbox",
        touchNavigation: true,
        loop: true
    });

    const videoLightbox = GLightbox({
        selector: ".video-lightbox",
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
});
