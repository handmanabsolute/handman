document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('.relative button');

    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            const passwordInput = document.getElementById('password_input');
            const passwordIcon = document.getElementById('password_icon');

            if (passwordInput && passwordIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                }
            }
        });
    }
});
