function setCheckboxesChecked(elementClicked, checkedValue) {
    const ancestorFieldset = elementClicked.closest("fieldset");
    const checkboxes = ancestorFieldset.querySelectorAll(
        'input[type="checkbox"]'
    );
    checkboxes.forEach(checkbox => (checkbox.checked = checkedValue));
}

function selectAllMotorhomesInFieldset() {}

document.querySelector("body").addEventListener("click", function(event) {
    if (event.target.classList.contains("select-all-vehicles")) {
        event.preventDefault();
        setCheckboxesChecked(event.target, true);
    }
    if (event.target.classList.contains("deselect-all-vehicles")) {
        event.preventDefault();
        setCheckboxesChecked(event.target, false);
    }
});
