function fetchRealTimeIndex(tableContainerId, fetchUrl) {
    const container = document.getElementById(tableContainerId);
    if (!container) return;

    fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById(tableContainerId);

        if (newContent) {
            container.innerHTML = newContent.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error fetching real-time data:', error);
    });
}

function initDeleteRowAjax(tableContainerId, fetchUrl) {
    document.addEventListener('submit', function (e) {
        const form = e.target;

        if (form && form.action && form.querySelector('input[name="_method"]')?.value === 'DELETE') {
            e.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            let originalText = 'Iya';
            if (submitButton) {
                originalText = submitButton.innerText;
                submitButton.disabled = true;
                submitButton.innerText = 'Memproses...';
            }

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    const modal = form.closest('[role="dialog"]');
                    if (modal && typeof closeModal === 'function') {
                        closeModal(modal.id);
                    }
                    fetchRealTimeIndex(tableContainerId, fetchUrl);
                } else {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerText = originalText;
                    }
                    alert('Gagal menghapus data. Silakan coba lagi.');
                }
            })
            .catch(error => {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerText = originalText;
                }
                console.error('Error deleting data:', error);
            });
        }
    });
}

window.fetchRealTimeIndex = fetchRealTimeIndex;
window.initDeleteRowAjax = initDeleteRowAjax;
