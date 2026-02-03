import axios from 'axios';
import Alpine from 'alpinejs';

// Set axios defaults
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.timeout = 30000;

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Request interceptor for loading states
window.axios.interceptors.request.use(
    config => {
        // Add timestamp to prevent caching for GET requests
        if (config.method === 'get') {
            config.params = config.params || {};
            config.params._t = Date.now();
        }
        
        // Show loading indicator for non-GET requests
        if (config.method !== 'get') {
            document.dispatchEvent(new CustomEvent('ajax:start'));
        }
        
        return config;
    },
    error => {
        document.dispatchEvent(new CustomEvent('ajax:complete'));
        return Promise.reject(error);
    }
);

// Response interceptor
window.axios.interceptors.response.use(
    response => {
        document.dispatchEvent(new CustomEvent('ajax:complete'));
        
        // Show success message for non-GET requests if message exists
        if (response.config.method !== 'get' && response.data?.message) {
            setTimeout(() => {
                if (window.Alpine?.store('app')?.showToast) {
                    Alpine.store('app').showToast(response.data.message, 'success');
                }
            }, 100);
        }
        
        return response;
    },
    error => {
        document.dispatchEvent(new CustomEvent('ajax:complete'));
        
        // Handle different error types
        if (error.response) {
            switch (error.response.status) {
                case 401: // Unauthorized
                    if (!window.location.pathname.includes('/login')) {
                        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    }
                    break;
                    
                case 403: // Forbidden
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Accès non autorisé', 'error');
                    }
                    break;
                    
                case 404: // Not Found
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Ressource non trouvée', 'error');
                    }
                    break;
                    
                case 419: // Session expired
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Session expirée, veuillez vous reconnecter', 'error');
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                    }
                    break;
                    
                case 422: // Validation error
                    if (error.response.data?.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const input = document.querySelector(`[name="${key}"], [name="${key}[]"]`);
                            if (input) {
                                const parent = input.closest('.form-group') || input.parentElement;
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message text-red-600 dark:text-red-400 text-sm mt-1';
                                errorDiv.textContent = errors[key][0];
                                parent.appendChild(errorDiv);
                                input.classList.add('border-red-500');
                            }
                        });
                    }
                    break;
                    
                case 429: // Too many requests
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Trop de requêtes, veuillez patienter', 'error');
                    }
                    break;
                    
                case 500: // Server error
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Erreur serveur, veuillez réessayer plus tard', 'error');
                    }
                    break;
                    
                default:
                    if (window.Alpine?.store('app')?.showToast) {
                        Alpine.store('app').showToast('Une erreur est survenue', 'error');
                    }
            }
        } else if (error.request) {
            // Network error
            if (window.Alpine?.store('app')?.showToast) {
                Alpine.store('app').showToast('Erreur réseau, vérifiez votre connexion', 'error');
            }
        }
        
        return Promise.reject(error);
    }
);

// Initialize Alpine
window.Alpine = Alpine;

// Export for use in other modules
export { axios, Alpine };

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     forceTLS: false,
//     disableStats: true,
// });
