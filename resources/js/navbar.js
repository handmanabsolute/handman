document.addEventListener('DOMContentLoaded', () => {
    function getSidebar() {
        return document.getElementById('sidebar');
    }
    function getSidebarBackdrop() {
        return document.getElementById('sidebar-backdrop');
    }
    function getUserMenu() {
        return document.getElementById('user-menu');
    }
    function getNotifMenu() {
        return document.getElementById('notif-menu');
    }

    function openSidebar() {
        const sidebar = getSidebar();
        const backdrop = getSidebarBackdrop();
        if (!sidebar || !backdrop) return;

        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        backdrop.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        }, 20);
    }

    function closeSidebar() {
        const sidebar = getSidebar();
        const backdrop = getSidebarBackdrop();
        if (!sidebar || !backdrop) return;

        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');

        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
    }

    function toggleUserMenu(event) {
        const userMenu = getUserMenu();
        if (!userMenu) return;

        event.stopPropagation();
        closeNotifMenu();

        if (userMenu.classList.contains('hidden')) {
            userMenu.classList.remove('hidden');
            setTimeout(() => {
                userMenu.classList.remove('opacity-0', 'scale-95');
                userMenu.classList.add('opacity-100', 'scale-100');
            }, 20);
        } else {
            closeUserMenu();
        }
    }

    function closeUserMenu() {
        const userMenu = getUserMenu();
        if (userMenu && !userMenu.classList.contains('hidden')) {
            userMenu.classList.remove('opacity-100', 'scale-100');
            userMenu.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                userMenu.classList.add('hidden');
            }, 100);
        }
    }

    function toggleNotifMenu(event) {
        const notifMenu = getNotifMenu();
        if (!notifMenu) return;

        event.stopPropagation();
        closeUserMenu();

        if (notifMenu.classList.contains('hidden')) {
            notifMenu.classList.remove('hidden');
            setTimeout(() => {
                notifMenu.classList.remove('opacity-0', 'scale-95');
                notifMenu.classList.add('opacity-100', 'scale-100');
            }, 20);
        } else {
            closeNotifMenu();
        }
    }

    function closeNotifMenu() {
        const notifMenu = getNotifMenu();
        if (notifMenu && !notifMenu.classList.contains('hidden')) {
            notifMenu.classList.remove('opacity-100', 'scale-100');
            notifMenu.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                notifMenu.classList.add('hidden');
            }, 100);
        }
    }

    document.addEventListener('click', (event) => {
        const openSidebarBtn = event.target.closest('#open-sidebar');
        const closeSidebarBtn = event.target.closest('#close-sidebar');
        const sidebarBackdrop = event.target.closest('#sidebar-backdrop');
        const userMenuBtn = event.target.closest('#user-menu-btn');
        const notifMenuBtn = event.target.closest('#notif-menu-btn');
        const mobileSearchBtn = event.target.closest('#mobile-search-btn');
        const closeMobileSearchBtn = event.target.closest('#close-mobile-search');

        if (openSidebarBtn) {
            const sidebar = getSidebar();
            if (sidebar && sidebar.classList.contains('translate-x-0')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        } else if (closeSidebarBtn) {
            closeSidebar();
        } else if (sidebarBackdrop) {
            closeSidebar();
        } else if (userMenuBtn) {
            toggleUserMenu(event);
        } else if (notifMenuBtn) {
            toggleNotifMenu(event);
        } else if (mobileSearchBtn) {
            const searchForm = document.getElementById('navbar-search-form');
            if (searchForm) {
                searchForm.classList.add('mobile-search-active');
                const searchInput = document.getElementById('navbar-search-input');
                if (searchInput) searchInput.focus();
            }
        } else if (closeMobileSearchBtn) {
            const searchForm = document.getElementById('navbar-search-form');
            if (searchForm) {
                searchForm.classList.remove('mobile-search-active');
            }
        } else {
            const userMenu = getUserMenu();
            const notifMenu = getNotifMenu();
            if (userMenu && !userMenu.contains(event.target)) {
                closeUserMenu();
            }
            if (notifMenu && !notifMenu.contains(event.target)) {
                closeNotifMenu();
            }
        }
    });

    const form = document.getElementById('navbar-search-form');
    const categorySelect = document.getElementById('navbar-search-category');
    if (form && categorySelect) {
        const updateAction = () => {
            form.action = categorySelect.value;
        };
        updateAction();
        categorySelect.addEventListener('change', updateAction);
        form.addEventListener('submit', function() {
            updateAction();
        });
    }
});

function deleteNotification(event, id, url, csrfToken, buttonEl) {
    event.stopPropagation();
    event.preventDefault();

    const itemEl = buttonEl.closest('.relative.group');
    if (!itemEl) return;

    itemEl.style.transition = 'all 0.25s ease';
    itemEl.style.opacity = '0';
    itemEl.style.transform = 'scale(0.95)';

    const isUnread = itemEl.querySelector('.bg-red-500') !== null;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            _method: 'DELETE'
        })
    })
    .then(response => {
        if (response.ok) {
            setTimeout(() => {
                itemEl.remove();

                const remaining = document.querySelectorAll('#notif-menu .relative.group');
                if (remaining.length === 0) {
                    const listContainer = document.querySelector('#notif-menu .divide-y');
                    if (listContainer) {
                        listContainer.innerHTML = `
                            <div class="p-8 text-center text-gray-400 flex flex-col items-center gap-2">
                                <i class="fa-regular fa-bell-slash text-xl text-gray-300"></i>
                                <p class="text-xs font-medium">Tidak ada notifikasi baru.</p>
                            </div>
                        `;
                    }
                }

                if (isUnread) {
                    const badge = document.getElementById('notif-badge');
                    const headerBadge = document.getElementById('notif-header-badge');

                    if (badge) {
                        let count = parseInt(badge.textContent.trim(), 10) - 1;
                        if (count <= 0) {
                            badge.remove();
                        } else {
                            badge.textContent = count;
                        }
                    }

                    if (headerBadge) {
                        let count = parseInt(headerBadge.textContent.trim(), 10) - 1;
                        if (count <= 0) {
                            headerBadge.remove();
                        } else {
                            headerBadge.textContent = count + ' baru';
                        }
                    }
                }
            }, 250);
        } else {
            itemEl.style.opacity = '1';
            itemEl.style.transform = 'none';
            alert('Gagal menghapus notifikasi.');
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
        itemEl.style.opacity = '1';
        itemEl.style.transform = 'none';
        alert('Terjadi kesalahan.');
    });
}

function deleteAllNotifications(event, url, csrfToken, buttonEl) {
    event.stopPropagation();
    event.preventDefault();

    const notifMenu = document.getElementById('notif-menu');
    if (!notifMenu) return;

    const items = notifMenu.querySelectorAll('.relative.group');
    items.forEach(item => {
        item.style.transition = 'all 0.25s ease';
        item.style.opacity = '0';
        item.style.transform = 'scale(0.95)';
    });

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            _method: 'DELETE'
        })
    })
    .then(response => {
        if (response.ok) {
            setTimeout(() => {
                items.forEach(item => item.remove());

                const listContainer = notifMenu.querySelector('.max-h-80');
                if (listContainer) {
                    listContainer.innerHTML = `
                        <div class="p-8 text-center text-gray-400 flex flex-col items-center gap-2">
                            <i class="fa-regular fa-bell-slash text-xl text-gray-300"></i>
                            <p class="text-xs font-medium">Tidak ada notifikasi baru.</p>
                        </div>
                    `;
                }

                // Hapus badge
                const badge = document.getElementById('notif-badge');
                const headerBadge = document.getElementById('notif-header-badge');
                if (badge) badge.remove();
                if (headerBadge) headerBadge.remove();

                // Hapus tombol-tombol di header menu
                const actionsContainer = notifMenu.querySelector('.notif-actions-header');
                if (actionsContainer) actionsContainer.remove();
            }, 250);
        } else {
            items.forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'none';
            });
            alert('Gagal menghapus semua notifikasi.');
        }
    })
    .catch(error => {
        console.error('Error deleting all notifications:', error);
        items.forEach(item => {
            item.style.opacity = '1';
            item.style.transform = 'none';
        });
        alert('Terjadi kesalahan.');
    });
}

window.deleteNotification = deleteNotification;
window.deleteAllNotifications = deleteAllNotifications;
