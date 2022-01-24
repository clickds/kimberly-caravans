import Plyr from "plyr";

document.addEventListener("DOMContentLoaded", function() {
    const videos = document.querySelectorAll(".plyr-video");

    videos.forEach(video => {
        const player = new Plyr(video);
    });

    const videoBanners = document.querySelectorAll(".plyr-video-banner");
    videoBanners.forEach(video => {
        const player = new Plyr(video, {
            autoplay: true,
            controls: false,
            loop: {
                active: true
            },
            muted: true
        });
    });
});
