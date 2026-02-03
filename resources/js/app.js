import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Enregistrer le plugin persist
Alpine.plugin(persist);

// Initialisation d'Alpine
window.Alpine = Alpine;

// Store global avec persistance
Alpine.store('app', {
    // Dark mode persistant
    darkMode: Alpine.$persist(true).as('darkMode'),
    
    // Sidebar (persistant pour desktop)
    sidebarOpen: Alpine.$persist(true).as('sidebarOpen'),
    
    // Mobile menu
    mobileMenuOpen: false,
    
    // Initialisation
    init() {
        // Appliquer le dark mode au chargement
        this.applyDarkMode();
        
        // Watcher pour le dark mode
        this.$watch('darkMode', value => {
            this.applyDarkMode();
        });
        
        // Gérer la responsivité du sidebar
        this.handleSidebarResponsive();
        window.addEventListener('resize', () => this.handleSidebarResponsive());
        
        // Fermer le menu mobile lors du clic en dehors
        document.addEventListener('click', (e) => {
            if (this.mobileMenuOpen && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('[data-sidebar-toggle]')) {
                this.mobileMenuOpen = false;
            }
        });
    },
    
    // Méthodes
    applyDarkMode() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
    },
    
    toggleSidebar() {
        if (window.innerWidth < 1024) {
            // Mobile: toggle le menu sans persistance
            this.mobileMenuOpen = !this.mobileMenuOpen;
        } else {
            // Desktop: toggle avec persistance
            this.sidebarOpen = !this.sidebarOpen;
        }
    },
    
    handleSidebarResponsive() {
        if (window.innerWidth < 1024) {
            // Sur mobile, toujours fermé au chargement
            this.mobileMenuOpen = false;
        }
    },
    
    // Toast notification system
    showToast(message, type = 'success', duration = 3500) {
        const toast = document.createElement('div');
        const icons = {
            success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>`,
            error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>`
        };
        
        const colors = {
            success: 'bg-emerald-600 border-emerald-500',
            error: 'bg-red-600 border-red-500',
            warning: 'bg-amber-600 border-amber-500',
            info: 'bg-blue-600 border-blue-500'
        };
        
        toast.className = `fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl text-white font-medium transform translate-y-20 opacity-0 transition-all duration-300 ${colors[type] || colors.info} border-l-4`;
        
        toast.innerHTML = `
            <div class="flex-shrink-0">
                ${icons[type] || icons.info}
            </div>
            <div class="flex-1 text-sm">
                ${message}
            </div>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:opacity-80 transition-opacity">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 100);
        
        // Animate out
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
});

// Composants Alpine personnalisés
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

Alpine.data('tabs', (defaultTab = 0) => ({
    activeTab: defaultTab,
    setTab(index) {
        this.activeTab = index;
    }
}));

Alpine.data('accordion', () => ({
    openItems: [],
    toggle(index) {
        if (this.openItems.includes(index)) {
            this.openItems = this.openItems.filter(i => i !== index);
        } else {
            this.openItems.push(index);
        }
    },
    isOpen(index) {
        return this.openItems.includes(index);
    }
}));

// Directives Alpine personnalisées
Alpine.directive('tooltip', (el, { expression }, { evaluate }) => {
    const text = evaluate(expression);
    const tooltip = document.createElement('div');
    
    tooltip.className = 'absolute z-50 px-3 py-2 text-xs font-medium text-white bg-slate-900 dark:bg-slate-700 rounded-lg shadow-lg whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200';
    tooltip.textContent = text;
    tooltip.style.bottom = 'calc(100% + 8px)';
    tooltip.style.left = '50%';
    tooltip.style.transform = 'translateX(-50%)';
    
    el.style.position = 'relative';
    el.appendChild(tooltip);
    
    el.addEventListener('mouseenter', () => {
        tooltip.style.opacity = '1';
    });
    
    el.addEventListener('mouseleave', () => {
        tooltip.style.opacity = '0';
    });
});

// Démarrage d'Alpine
Alpine.start();

// Fonctions helper globales
window.showToast = (message, type = 'success', duration = 3500) => {
    if (window.Alpine?.store('app')?.showToast) {
        Alpine.store('app').showToast(message, type, duration);
    }
};

// Confirmation dialog
window.confirmAction = (message, callback) => {
    if (confirm(message)) {
        callback();
    }
};

// Copier dans le presse-papier
window.copyToClipboard = (text) => {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copié dans le presse-papier', 'success', 2000);
    }).catch(() => {
        showToast('Erreur lors de la copie', 'error', 2000);
    });
};

// Formater les nombres
window.formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(num);
};

// Formater les dates
window.formatDate = (date, format = 'short') => {
    const options = {
        short: { year: 'numeric', month: 'short', day: 'numeric' },
        long: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' },
        time: { hour: '2-digit', minute: '2-digit' },
        full: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long', hour: '2-digit', minute: '2-digit' }
    };
    
    return new Intl.DateTimeFormat('fr-FR', options[format] || options.short).format(new Date(date));
};

// Debounce function
window.debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Smooth scroll to element
window.scrollToElement = (selector, offset = 0) => {
    const element = document.querySelector(selector);
    if (element) {
        const top = element.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    }
};

// Loading indicator
window.showLoading = (show = true) => {
    let loader = document.getElementById('global-loader');
    
    if (!loader && show) {
        loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm';
        loader.innerHTML = `
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 border-4 border-orange-600 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-slate-900 dark:text-white font-medium">Chargement...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loader);
        document.body.style.overflow = 'hidden';
    } else if (loader && !show) {
        loader.remove();
        document.body.style.overflow = '';
    }
};

// Event listeners pour améliorer l'expérience
document.addEventListener('DOMContentLoaded', () => {
    // Ajouter des animations aux éléments lors du scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observer les cartes et sections
    document.querySelectorAll('.stat-card, .action-card, .glass-card').forEach(el => {
        observer.observe(el);
    });
    
    // Gérer les erreurs de validation
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', () => {
            // Supprimer les messages d'erreur lors de la saisie
            const errorMessage = input.parentElement.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
                input.classList.remove('border-red-500');
            }
        });
    });
});

// Export pour utilisation dans d'autres modules
export { Alpine };