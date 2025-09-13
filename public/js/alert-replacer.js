/**
 * Universal Alert Replacement Script
 * This script replaces native alert() and confirm() with SweetAlert2 equivalents
 * Add this script to any page to automatically upgrade all alert dialogs
 */

// Load notifications.js if not already loaded
if (typeof window.notifications === 'undefined') {
    const script = document.createElement('script');
    script.src = '/js/notifications.js';
    script.onload = initializeAlertReplacement;
    document.head.appendChild(script);
} else {
    initializeAlertReplacement();
}

function initializeAlertReplacement() {
    // Store original functions
    const originalAlert = window.alert;
    const originalConfirm = window.confirm;

    // Override native alert
    window.alert = function(message) {
        if (typeof window.swalInfo === 'function') {
            window.swalInfo(message, { title: 'Informasi' });
        } else {
            // Fallback to original
            originalAlert(message);
        }
    };

    // Override native confirm - returns Promise for compatibility
    window.confirm = function(message) {
        if (typeof window.swalConfirm === 'function') {
            return window.swalConfirm(message, {
                title: 'Konfirmasi',
                confirmText: 'Ya',
                cancelText: 'Tidak'
            });
        } else {
            // Fallback to original
            return Promise.resolve(originalConfirm(message));
        }
    };

    // Custom alert functions for better UX
    window.alertSuccess = function(message, title = 'Berhasil!') {
        return window.swalSuccess(message, { title });
    };

    window.alertError = function(message, title = 'Error!') {
        return window.swalError(message, { title });
    };

    window.alertWarning = function(message, title = 'Peringatan!') {
        return window.swalWarning(message, { title });
    };

    window.alertInfo = function(message, title = 'Informasi') {
        return window.swalInfo(message, { title });
    };

    // Enhanced confirm with different types
    window.confirmDanger = function(message, title = 'Konfirmasi Hapus') {
        return window.swalConfirm(message, {
            title,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            type: 'danger'
        });
    };

    window.confirmWarning = function(message, title = 'Peringatan') {
        return window.swalConfirm(message, {
            title,
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            type: 'warning'
        });
    };

    console.log('✅ Alert Replacement System initialized successfully');
}

// Helper function to convert callback-based confirm to Promise-based
window.confirmAsync = async function(message, callback) {
    const result = await window.confirm(message);
    if (callback && typeof callback === 'function') {
        callback(result);
    }
    return result;
};

// Auto-convert form submissions with confirm attributes
document.addEventListener('DOMContentLoaded', function() {
    // Handle forms with onsubmit="return confirm(...)"
    const forms = document.querySelectorAll('form[onsubmit*="confirm"]');
    forms.forEach(form => {
        const originalOnsubmit = form.onsubmit;
        form.onsubmit = null;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Extract confirm message from original onsubmit
            const onsubmitStr = originalOnsubmit ? originalOnsubmit.toString() : '';
            const confirmMatch = onsubmitStr.match(/confirm\s*\(\s*['"`](.+?)['"`]\s*\)/);

            if (confirmMatch) {
                const message = confirmMatch[1];
                const confirmed = await window.confirmDanger(message);
                if (confirmed) {
                    // Remove the event listener to avoid infinite loop
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                }
            } else {
                // If can't parse confirm, just submit
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            }
        });
    });

    // Handle buttons with onclick confirm
    const buttonsWithConfirm = document.querySelectorAll('button[onclick*="confirm"], a[onclick*="confirm"]');
    buttonsWithConfirm.forEach(button => {
        const originalOnclick = button.onclick;
        button.onclick = null;

        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const onclickStr = originalOnclick ? originalOnclick.toString() : '';
            const confirmMatch = onclickStr.match(/confirm\s*\(\s*['"`](.+?)['"`]\s*\)/);

            if (confirmMatch) {
                const message = confirmMatch[1];
                const isDanger = message.toLowerCase().includes('hapus') ||
                                message.toLowerCase().includes('delete') ||
                                message.toLowerCase().includes('remove');

                const confirmed = isDanger ?
                    await window.confirmDanger(message) :
                    await window.confirm(message);

                if (confirmed) {
                    // Execute original action without confirm
                    const cleanOnclick = onclickStr.replace(/return\s+confirm\s*\([^)]+\)\s*[;&]?\s*/gi, '');
                    const func = new Function(cleanOnclick.match(/function[^{]+\{([\s\S]*)\}$/)?.[1] || '');
                    func.call(this);
                }
            }
        });
    });
});