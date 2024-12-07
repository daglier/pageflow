const loginsec = document.querySelector('.login-section');
const loginlink = document.querySelector('.login-link');
const registerlink = document.querySelector('.register-link');
const recoverLink = document.querySelector('.recover-link');
const backToLoginLink = document.querySelector('.back-to-login-link');

const passwordFieldLogin = document.getElementById('password-login'),
    passwordFieldRegister = document.getElementById('password-register'),
    passwordFieldRecovery = document.getElementById('password-recovery'),
    toggleLoginPassword = document.getElementById('toggle-login-password'),
    toggleRegisterPassword = document.getElementById('toggle-register-password'),
    toggleRecoveryPassword = document.getElementById('toggle-recovery-password');


// Función para añadir los event listener de los toggle de las contraseñas
const togglePasswordVisibility = (toggleButton, passwordField) => {
    toggleButton.addEventListener('click', () => {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        toggleButton.classList.toggle('fa-eye');
        toggleButton.classList.toggle('fa-eye-slash');
    });
};


// Añadir una clase para mostrar el formulario de recuperación en lugar del de login/register
loginlink.addEventListener('click', () => {
    loginsec.classList.remove('active');
    loginsec.classList.remove('recover-active'); 
});

registerlink.addEventListener('click', () => {
    loginsec.classList.add('active');
});

recoverLink.addEventListener('click', () => {
    loginsec.classList.add('recover-active'); 
});

backToLoginLink.addEventListener('click', () => {
    loginsec.classList.remove('active');
    loginsec.classList.remove('recover-active');
});


// Asignar las funciones para alternar la visibilidad de las contraseñas en los formularios
togglePasswordVisibility(toggleLoginPassword, passwordFieldLogin);
togglePasswordVisibility(toggleRegisterPassword, passwordFieldRegister);
togglePasswordVisibility(toggleRecoveryPassword, passwordFieldRecovery);