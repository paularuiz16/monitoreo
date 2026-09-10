/**
 * ==========================================================================
 * AvícolaPro Control - Authentication Animations & Interactions
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Toggle Password Visibility Animation
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
            toggleBtn.classList.toggle('active', isPassword);
        });
    }

    // 2. Real-time Password Strength Visual Meter Animation
    const passInput = document.getElementById('password');
    const strengthBars = document.querySelectorAll('.strength-meter-bar');
    const strengthText = document.getElementById('strengthText');

    if (passInput && strengthBars.length > 0) {
        passInput.addEventListener('input', (e) => {
            const val = e.target.value;
            let score = 0;

            if (val.length >= 6) score++;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            strengthBars.forEach((bar, index) => {
                bar.className = 'strength-meter-bar';
                if (index < score) {
                    if (score <= 1) {
                        bar.classList.add('danger');
                    } else if (score <= 2) {
                        bar.classList.add('warning');
                    } else {
                        bar.classList.add('active');
                    }
                }
            });

            if (strengthText) {
                if (val.length === 0) {
                    strengthText.textContent = 'Ingrese una contraseña segura';
                    strengthText.className = 'small text-muted';
                } else if (score <= 1) {
                    strengthText.textContent = 'Fortaleza: Débil (agregue más caracteres)';
                    strengthText.className = 'small text-danger fw-semibold';
                } else if (score <= 2) {
                    strengthText.textContent = 'Fortaleza: Media (agregue mayúsculas y números)';
                    strengthText.className = 'small text-warning fw-semibold';
                } else {
                    strengthText.textContent = 'Fortaleza: Fuerte y segura para producción';
                    strengthText.className = 'small text-success fw-semibold';
                }
            }
        });
    }
});

// 3. Quick Demo Credential Autofill with feedback animation
window.fillCredentials = function (email, pass) {
    const emailField = document.getElementById('email');
    const passField = document.getElementById('password');

    if (emailField && passField) {
        emailField.value = email;
        passField.value = pass;

        // Visual flash animation
        [emailField, passField].forEach((field) => {
            field.style.transition = 'all 0.3s ease';
            field.style.backgroundColor = '#e8f5e9';
            setTimeout(() => {
                field.style.backgroundColor = '';
            }, 400);
        });
    }
};
