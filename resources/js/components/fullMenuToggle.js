document.addEventListener("DOMContentLoaded", function() {
    const fullMenu = document.querySelector(".full-menu");
    const fullMenuButtons = document.querySelectorAll(".full-menu-button");

    fullMenuButtons.forEach(button => {
        button.addEventListener("click", toggleFullMenu);
    });

    function toggleFullMenu(event) {
        event.preventDefault();
        fullMenu.classList.toggle("invisible");
        fullMenu.classList.toggle("opacity-0");
    }
});
