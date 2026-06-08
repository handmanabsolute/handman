function executeGlobalAjaxSubmit(formId, modalId) {
    const modal = document.getElementById(modalId);
    let confirmBtn = null;
    let cancelBtn = null;
    let originalConfirmText = 'Iya';

    if (modal) {
        confirmBtn = modal.querySelector('button[onclick*="executeGlobalAjaxSubmit"]');
        cancelBtn = modal.querySelector('button[onclick*="closeModal"]');
        if (confirmBtn) {
            originalConfirmText = confirmBtn.innerText;
            confirmBtn.disabled = true;
            confirmBtn.innerText = 'Memproses...';
        }
        if (cancelBtn) {
            cancelBtn.disabled = true;
        }
    }

    const form = document.getElementById(formId);
    if (!form) {
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerText = originalConfirmText;
        }
        if (cancelBtn) {
            cancelBtn.disabled = false;
        }
        return;
    }

    const formData = new FormData(form);
    const fallbackRedirect = form.getAttribute('data-redirect');
    clearAllFormErrors(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(async response => {
            const data = await response.json();

            if (typeof closeModal === 'function' && modalId) {
                closeModal(modalId);
            }

            if (response.ok) {
                window.location.href = data.redirect || fallbackRedirect;
            } else {
                // Restore button states in case modal is reopened or form needs correction
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.innerText = originalConfirmText;
                }
                if (cancelBtn) {
                    cancelBtn.disabled = false;
                }

                if (response.status === 422) {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            showFormFieldError(form, key, data.errors[key][0]);
                        });
                    }
                } else {
                    alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
                }
            }
        })
        .catch(error => {
            if (typeof closeModal === 'function' && modalId) {
                closeModal(modalId);
            }
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalConfirmText;
            }
            if (cancelBtn) {
                cancelBtn.disabled = false;
            }
            console.error('Error:', error);
            alert('Gagal mengirim data.');
        });
}

function initRealTimeValidation(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    const fields = form.querySelectorAll('input, select, textarea');

    fields.forEach(field => {
        const eventType = field.tagName === 'SELECT' || field.type === 'date' ? 'change' : 'input';
        field.addEventListener(eventType, () => {
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-Validate-Only': 'true'
                }
            })
                .then(async response => {
                    if (response.ok) {
                        const data = await response.json();
                        const fieldName = field.name;

                        if (data.valid === false && data.errors && data.errors[fieldName]) {
                            showFormFieldError(form, fieldName, data.errors[fieldName][0]);
                        } else if (data.valid === true || !data.errors || !data.errors[fieldName]) {
                            clearSingleFormError(form, fieldName);
                        }
                    }
                })
                .catch(() => { });
        });
    });
}

function showFormFieldError(form, key, message) {
    const errorEl = form.querySelector(`#error-${key}`) || document.getElementById(`error-${key}`);
    const inputEl = form.querySelector(`[name="${key}"]`);
    if (errorEl) {
        errorEl.innerText = message;
        errorEl.classList.remove('hidden');
    }
    if (inputEl) {
        inputEl.classList.remove('border-gray-200', 'focus:border-[#3B28CC]', 'focus:ring-[#3B28CC]');
        inputEl.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    }
}

function clearSingleFormError(form, key) {
    const errorEl = form.querySelector(`#error-${key}`) || document.getElementById(`error-${key}`);
    const inputEl = form.querySelector(`[name="${key}"]`);
    if (errorEl) {
        errorEl.classList.add('hidden');
        errorEl.innerText = '';
    }
    if (inputEl) {
        inputEl.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        inputEl.classList.add('border-gray-200', 'focus:border-[#3B28CC]', 'focus:ring-[#3B28CC]');
    }
}

function clearAllFormErrors(form) {
    if (!form) return;

    form.querySelectorAll('.error-msg').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });
    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        el.classList.add('border-gray-200', 'focus:border-[#3B28CC]', 'focus:ring-[#3B28CC]');
    });
}

window.executeGlobalAjaxSubmit = executeGlobalAjaxSubmit;
window.initRealTimeValidation = initRealTimeValidation;
