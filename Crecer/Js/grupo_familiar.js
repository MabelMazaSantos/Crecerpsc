
function toggleGrupoFamiliarInput() {
    var grupoFamiliarTextInput = document.getElementById("grupoFamiliarTextInput");
    var grupoFamiliarSelectInput = document.getElementById("grupoFamiliarSelectInput");

    if (grupoFamiliarSelectInput.style.display === "none") {
        grupoFamiliarSelectInput.style.display = "block";
        grupoFamiliarTextInput.style.display = "none";
    } else {
        grupoFamiliarSelectInput.style.display = "none";
        grupoFamiliarTextInput.style.display = "block";
    }
}
