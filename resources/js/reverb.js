import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const cleanMeta = (name) => {
    const meta = document.querySelector(`meta[name="${name}"]`);
    if (meta) {
        const val = meta.getAttribute('content');
        if (typeof val === 'string') {
            return val.replace(/^["']|["']$/g, '').trim();
        }
        return val;
    }
    return null;
};

const currentUserId = cleanMeta('user-id');
const currentUserRole = cleanMeta('user-role');
const currentUserDeptId = cleanMeta('user-departemen-id');

const reverbKey = cleanMeta('reverb-key');
const reverbHost = cleanMeta('reverb-host') || window.location.hostname;
const reverbPort = cleanMeta('reverb-port') || 8080;
const reverbScheme = cleanMeta('reverb-scheme') || 'http';

console.log('Connecting to Reverb:', { reverbKey, reverbHost, reverbPort, reverbScheme });

const echoInstance = new Echo({
    broadcaster: 'reverb',
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: reverbPort,
    wssPort: reverbPort,
    forceTLS: reverbScheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

if (currentUserId) {
    const updateAppBody = () => {
        console.log('Updating application body...');
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('app-body-container');
            const currentContainer = document.getElementById('app-body-container');
            if (newContainer && currentContainer) {
                currentContainer.innerHTML = newContainer.innerHTML;
                console.log('App body updated successfully');
            }
        })
        .catch(error => {
            console.error('Error fetching real-time body update:', error);
        });
    };

    const updateNotifications = () => {
        console.log('Updating notifications only...');
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newNotifBtn = doc.getElementById('notif-menu-btn');
            const newNotifMenu = doc.getElementById('notif-menu');
            const currentNotifBtn = document.getElementById('notif-menu-btn');
            const currentNotifMenu = document.getElementById('notif-menu');
            
            if (newNotifBtn && currentNotifBtn) {
                currentNotifBtn.outerHTML = newNotifBtn.outerHTML;
            }
            if (newNotifMenu && currentNotifMenu) {
                const wasHidden = currentNotifMenu.classList.contains('hidden');
                currentNotifMenu.outerHTML = newNotifMenu.outerHTML;
                const freshNotifMenu = document.getElementById('notif-menu');
                if (freshNotifMenu) {
                    if (wasHidden) {
                        freshNotifMenu.classList.add('hidden');
                        freshNotifMenu.style.opacity = '0';
                        freshNotifMenu.style.transform = 'scale(0.95)';
                    } else {
                        freshNotifMenu.classList.remove('hidden');
                        freshNotifMenu.style.opacity = '1';
                        freshNotifMenu.style.transform = 'scale(1)';
                    }
                }
            }
            console.log('Notifications updated successfully');
        })
        .catch(error => {
            console.error('Error fetching real-time notifications update:', error);
        });
    };

    const isTaskPage = window.location.pathname.includes('/tugas') || window.location.pathname.includes('/staff/tugas');
    const isReportPage = window.location.pathname.includes('/laporan') || window.location.pathname.includes('/admin/laporan') || window.location.pathname.includes('/staff/laporan');
    const isDashboard = window.location.pathname.includes('/dashboard');

    const handleTugasEvent = (e) => {
        console.log('Tugas event received:', e);
        if (currentUserRole === 'staff' && e.action === 'created') {
            if (isTaskPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        } else if (currentUserRole === 'manager' && e.action === 'submitted') {
            if (isTaskPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        } else if (currentUserRole === 'staff' && e.action === 'reviewed') {
            if (isTaskPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        } else if (e.action === 'updated') {
            if (isTaskPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        }
    };

    const handleLaporanEvent = (e) => {
        console.log('Laporan event received:', e);
        if (currentUserRole === 'admin' && e.action === 'created') {
            if (isReportPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        } else if (e.action === 'responded' && String(e.userId) === String(currentUserId)) {
            if (isReportPage || isDashboard) {
                updateAppBody();
            } else {
                updateNotifications();
            }
        }
    };

    if (currentUserDeptId) {
        console.log('Subscribed to task channel:', `departemen-${currentUserDeptId}`);
        echoInstance.channel(`departemen-${currentUserDeptId}`)
            .listen('.RealtimeTugasEvent', handleTugasEvent)
            .listen('RealtimeTugasEvent', handleTugasEvent)
            .listen('.App\\Events\\RealtimeTugasEvent', handleTugasEvent);
    }

    console.log('Subscribed to laporan channel');
    echoInstance.channel('laporan')
        .listen('.RealtimeLaporanEvent', handleLaporanEvent)
        .listen('RealtimeLaporanEvent', handleLaporanEvent)
        .listen('.App\\Events\\RealtimeLaporanEvent', handleLaporanEvent);
}
