document.addEventListener("DOMContentLoaded", function() {
    const slideToggles = document.querySelectorAll(".slide-toggle");

    slideToggles.forEach(slideToggle => {
        const openButton = slideToggle.querySelector("[data-toggle='open']");
        const closeButton = slideToggle.querySelector("[data-toggle='close']");
        const contentContainer = slideToggle.querySelector(
            "[data-toggle='content']"
        );
        openButton.addEventListener(
            "click",
            () => slideOpen(contentContainer, openButton, closeButton),
            false
        );
        closeButton.addEventListener(
            "click",
            () => slideClose(contentContainer, openButton, closeButton),
            false
        );
    });
});

function slideOpen(contentContainer, openButton, closeButton) {
    openButton.classList.toggle("hidden");
    closeButton.classList.toggle("hidden");
    const height = getHeight(contentContainer); // Get the natural height
    contentContainer.classList.add("is-visible"); // Make the element visible
    contentContainer.style.height = height; // Update the height

    // Once the transition is complete, remove the inline height so the content can scale responsively
    window.setTimeout(function() {
        contentContainer.style.height = "";
    }, 350);
}

function slideClose(contentContainer, openButton, closeButton) {
    // Give the element a height to change from
    contentContainer.style.height = contentContainer.scrollHeight + "px";

    // Set the height back to 0
    window.setTimeout(function() {
        contentContainer.style.height = "0";
    }, 1);

    // When the transition is complete, hide it
    window.setTimeout(function() {
        contentContainer.classList.remove("is-visible");
        openButton.classList.toggle("hidden");
        closeButton.classList.toggle("hidden");
    }, 350);
}

function getHeight(element) {
    element.style.display = "block"; // Make it visible
    var height = element.scrollHeight + "px"; // Get it's height
    element.style.display = ""; //  Hide it again
    return height;
}
