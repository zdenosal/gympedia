const registrationForm = document.querySelector('.registration-form');
const loginForm = document.querySelector('.login-form');
const switchToLogin = document.querySelector('.switch-to-login');
const switchToRegistration = document.querySelector('.switch-to-registration');
const closeRegistrationModal = document.querySelector('.close-registration-modal');
const closeLoginModal = document.querySelector('.close-login-modal');
const joinUsButton = document.querySelector('.open-registration-modal');

switchToLogin.addEventListener('click', () => {
    registrationForm.close();
    loginForm.showModal();
});

switchToRegistration.addEventListener('click', () => {
    loginForm.close();
    registrationForm.showModal();
});

joinUsButton.addEventListener('click', () => {
    registrationForm.showModal();
});

closeRegistrationModal.addEventListener('click', () => {
    registrationForm.close();
});

closeLoginModal.addEventListener('click', () => {
    loginForm.close();
});

const passwordInput = document.querySelector('#password');
const repeatPasswordInput = document.querySelector('#rptpassword');

    repeatPasswordInput.addEventListener('input', () => {
        if (repeatPasswordInput.value !== passwordInput.value) {
            repeatPasswordInput.setCustomValidity('Passwords do not match');
            repeatPasswordInput.style.borderColor = 'red';
            repeatPasswordInput.style.color = 'red';
        } else {
            repeatPasswordInput.setCustomValidity('');
            repeatPasswordInput.style.borderColor = '';
            repeatPasswordInput.style.color = '';
        }
    });