document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');
    const openSidebarBtn = document.getElementById('open-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');

    const userMenuBtn = document.getElementById('user-menu-btn');
    const userMenu = document.getElementById('user-menu');

    function openSidebar() {
        if (!sidebar || !sidebarBackdrop) return;

        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        sidebarBackdrop.classList.remove('hidden');
        setTimeout(() => {
            sidebarBackdrop.classList.remove('opacity-0');
            sidebarBackdrop.classList.add('opacity-100');
        }, 20);
    }

    function closeSidebar() {
        if (!sidebar || !sidebarBackdrop) return;

        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');

        sidebarBackdrop.classList.remove('opacity-100');
        sidebarBackdrop.classList.add('opacity-0');

        setTimeout(() => {
            sidebarBackdrop.classList.add('hidden');
        }, 300);
    }

    if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);


    function toggleUserMenu(event) {
        if (!userMenu) return;

        event.stopPropagation();

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
        if (userMenu && !userMenu.classList.contains('hidden')) {
            userMenu.classList.remove('opacity-100', 'scale-100');
            userMenu.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                userMenu.classList.add('hidden');
            }, 100);
        }
    }

    if (userMenuBtn) userMenuBtn.addEventListener('click', toggleUserMenu);

    document.addEventListener('click', (event) => {
        if (userMenu && !userMenu.contains(event.target)) {
            closeUserMenu();
        }
    });
});
