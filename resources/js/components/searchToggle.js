document.addEventListener("DOMContentLoaded", function() {
    var elements = document.getElementsByClassName("toggle-search");
    for (var i = 0; i < elements.length; i++) {
        elements.item(i).onclick = function() {
            var mainSearchForm = document.getElementById("mainSearchForm");
            mainSearchForm.classList.toggle("hidden");
        };
    }

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        var isEscape = false;
        if ("key" in evt) {
            isEscape = evt.key === "Escape" || evt.key === "Esc";
        } else {
            isEscape = evt.keyCode === 27;
        }
        if (isEscape) {
            document.getElementById("mainSearchForm").classList.add("hidden");
        }
    };
});
