/**
 * ==========================================================================
 * AvícolaPro Control - Global Application Animations & UI Interactions
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle & Responsive Backdrop Animation
    const sidebarToggle = document.getElementById('sidebarToggle');
    const stitchSidebar = document.getElementById('stitchSidebar');
    let backdrop = document.querySelector('.sidebar-backdrop');

    if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        document.body.appendChild(backdrop);
    }

    if (sidebarToggle && stitchSidebar) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = stitchSidebar.classList.toggle('show');
            backdrop.classList.toggle('show', isOpen);
        });

        backdrop.addEventListener('click', () => {
            stitchSidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });
    }

    // 2. Smooth auto-dismissal for flash alerts after 6 seconds
    const flashAlerts = document.querySelectorAll('.alert-dismissible');
    flashAlerts.forEach((alert) => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 350);
        }, 6000);
    });

    // 3. Emergency Stop Protocol Interaction
    const emergencyBtn = document.getElementById('emergencyStopBtn');
    if (emergencyBtn) {
        emergencyBtn.addEventListener('click', () => {
            if (confirm('¿CONFIRMA ACTIVACIÓN DE PARADA DE EMERGENCIA?\n\nEsta acción detendrá inmediatamente actuadores mecánicos y mantendrá las cortinas en posición de seguridad.')) {
                emergencyBtn.classList.remove('btn-outline-danger');
                emergencyBtn.classList.add('btn-danger');
                emergencyBtn.innerHTML = '<span class="material-symbols-outlined fs-5">warning</span> PROTOCOLO ACTIVADO';
                alert('Protocolo de Seguridad SCADA transmitido exitosamente al Galpón 01.');
            }
        });
    }
});
