/**
 * Universal Notification System with SweetAlert2
 * Consistent notification styling across the application
 */

// Import SweetAlert2 if not already loaded
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(script);

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
    document.head.appendChild(link);
}

class NotificationSystem {
    constructor() {
        this.container = null;
        this.notifications = new Map();
        this.init();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-3';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notification-container');
        }
    }

    show(message, type = 'info', duration = 5000, options = {}) {
        const id = `notification-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

        // Remove existing notification if replacing
        if (options.replace && this.notifications.has(options.replace)) {
            this.remove(options.replace);
        }

        const notification = this.createNotification(id, message, type, options);
        this.container.appendChild(notification);
        this.notifications.set(id, notification);

        // Animate in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        });

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }

        return id;
    }

    createNotification(id, message, type, options) {
        const notification = document.createElement('div');
        notification.id = id;
        notification.className = this.getNotificationClasses(type);
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        notification.style.transition = 'all 0.3s ease-in-out';

        const config = this.getTypeConfig(type);

        notification.innerHTML = `
            <div class="flex p-4 max-w-sm w-full mx-4 sm:mx-0">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 ${config.iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${config.icon}
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    ${options.title ? `<p class="text-sm font-medium text-gray-900">${options.title}</p>` : ''}
                    <p class="text-sm ${options.title ? 'text-gray-500 mt-1' : 'font-medium text-gray-900'}">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" onclick="window.notifications.remove('${id}')">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        return notification;
    }

    getNotificationClasses(type) {
        const baseClasses = 'bg-white rounded-lg shadow-lg border-l-4';
        const typeClasses = {
            'info': 'border-blue-400',
            'success': 'border-green-400',
            'warning': 'border-yellow-400',
            'error': 'border-red-400'
        };

        return `${baseClasses} ${typeClasses[type] || typeClasses.info}`;
    }

    getTypeConfig(type) {
        const configs = {
            'info': {
                iconColor: 'text-blue-400',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            },
            'success': {
                iconColor: 'text-green-400',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            },
            'warning': {
                iconColor: 'text-yellow-400',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.682 16.5c-.77.833.192 2.5 1.732 2.5z"/>'
            },
            'error': {
                iconColor: 'text-red-400',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            }
        };

        return configs[type] || configs.info;
    }

    remove(id) {
        const notification = this.notifications.get(id);
        if (notification && notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
                this.notifications.delete(id);
            }, 300);
        }
    }

    clear() {
        this.notifications.forEach((notification, id) => {
            this.remove(id);
        });
    }

    // Convenience methods
    success(message, duration = 4000, options = {}) {
        return this.show(message, 'success', duration, options);
    }

    error(message, duration = 6000, options = {}) {
        return this.show(message, 'error', duration, options);
    }

    warning(message, duration = 5000, options = {}) {
        return this.show(message, 'warning', duration, options);
    }

    info(message, duration = 5000, options = {}) {
        return this.show(message, 'info', duration, options);
    }

    // Enhanced confirmation dialog with SweetAlert2
    async confirm(message, options = {}) {
        const config = {
            title: options.title || 'Konfirmasi',
            text: message,
            icon: this.getSweetAlertIcon(options.type || 'question'),
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Ya',
            cancelButtonText: options.cancelText || 'Batal',
            confirmButtonColor: options.type === 'danger' ? '#ef4444' : '#3b82f6',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            focusCancel: options.type === 'danger',
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-lg font-semibold',
                confirmButton: 'px-4 py-2 rounded-md font-medium',
                cancelButton: 'px-4 py-2 rounded-md font-medium'
            }
        };

        try {
            const result = await Swal.fire(config);
            return result.isConfirmed;
        } catch (error) {
            console.error('SweetAlert2 error:', error);
            // Fallback to native confirm
            return confirm(message);
        }
    }

    // SweetAlert2 methods
    async swalSuccess(message, options = {}) {
        return await Swal.fire({
            title: options.title || 'Berhasil!',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            timer: options.timer || 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-lg font-semibold text-green-800',
                confirmButton: 'px-4 py-2 rounded-md font-medium'
            }
        });
    }

    async swalError(message, options = {}) {
        return await Swal.fire({
            title: options.title || 'Error!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-lg font-semibold text-red-800',
                confirmButton: 'px-4 py-2 rounded-md font-medium'
            }
        });
    }

    async swalWarning(message, options = {}) {
        return await Swal.fire({
            title: options.title || 'Peringatan!',
            text: message,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f59e0b',
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-lg font-semibold text-yellow-800',
                confirmButton: 'px-4 py-2 rounded-md font-medium'
            }
        });
    }

    async swalInfo(message, options = {}) {
        return await Swal.fire({
            title: options.title || 'Informasi',
            text: message,
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b82f6',
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-lg font-semibold text-blue-800',
                confirmButton: 'px-4 py-2 rounded-md font-medium'
            }
        });
    }

    getSweetAlertIcon(type) {
        const iconMap = {
            'success': 'success',
            'error': 'error',
            'danger': 'error',
            'warning': 'warning',
            'info': 'info',
            'question': 'question'
        };
        return iconMap[type] || 'info';
    }
}

// Initialize global notification system
window.notifications = new NotificationSystem();

// Backwards compatibility and SweetAlert2 shortcuts
window.showNotification = (message, type, duration) => {
    return window.notifications.show(message, type, duration);
};

// SweetAlert2 global shortcuts
window.swalSuccess = (message, options = {}) => window.notifications.swalSuccess(message, options);
window.swalError = (message, options = {}) => window.notifications.swalError(message, options);
window.swalWarning = (message, options = {}) => window.notifications.swalWarning(message, options);
window.swalInfo = (message, options = {}) => window.notifications.swalInfo(message, options);
window.swalConfirm = (message, options = {}) => window.notifications.confirm(message, options);