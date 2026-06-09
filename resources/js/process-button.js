document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', function (event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';
        }

        // Disable all other buttons in the form or inside its parent modal/card container
        const container = form.closest('[role="dialog"]') || form.closest('.fixed') || form.closest('.bg-white') || form;
        container.querySelectorAll('button').forEach(btn => {
            if (btn !== submitButton) {
                btn.disabled = true;
            }
        });
    });
});
