document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', function (event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';
        }
    });
});
