function autoCompleteFields() {
    const dniField = document.getElementById('Dni');
    const passwordField = document.getElementById('Password');
    const repasswordField = document.getElementById('Repassword');
    const emailField = document.getElementById('Email');
    const usuarioField = document.getElementById('Usuario');

    dniField.addEventListener('input', () => {
        passwordField.value = dniField.value;
        repasswordField.value = dniField.value;
    });

    emailField.addEventListener('input', () => {
        usuarioField.value = emailField.value;
    });
}

document.addEventListener('DOMContentLoaded', autoCompleteFields);