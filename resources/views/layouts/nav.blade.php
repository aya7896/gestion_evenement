<nav class="sticky top-16 lg:top-18 z-40 backdrop-blur-xl bg-white/60 dark:bg-neutral-950/60 border-b border-neutral-200/50 dark:border-neutral-800/50">
    <div class="container-custom flex items-center h-14 px-5 sm:px-6 lg:px-8 overflow-x-auto scrollbar-hide">
        <div class="flex gap-2 md:gap-3">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high mr-2"></i> Dashboard
            </a>

            @auth
                @php
                    $isSuper = auth()->user()->role === 'super_admin';
                    $isAdmin = auth()->user()->collaborateurs()->first()?->role === 'admin_entreprise';
                @endphp

                @if($isSuper)
                    <a href="{{ route('evenements.index') }}" class="nav-item {{ request()->routeIs('evenements.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days mr-2"></i> Événements
                    </a>
                    <a href="{{ route('admin.entreprises.index') }}" class="nav-item {{ request()->routeIs('admin.entreprises.*') ? 'active' : '' }}">
                        <i class="fas fa-building mr-2"></i> Entreprises
                    </a>
                    <a href="{{ route('admin.collaborateurs.index') }}" class="nav-item {{ request()->routeIs('admin.collaborateurs.*') ? 'active' : '' }}">
                        <i class="fas fa-users-gear mr-2"></i> Collaborateurs
                    </a>
                @elseif($isAdmin)
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-gauge-high mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('evenements.index') }}" class="nav-item {{ request()->routeIs('evenements.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days mr-2"></i> Événements
                    </a>
                    <a href="{{ route('ateliers.index') }}" class="nav-item {{ request()->routeIs('ateliers.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher mr-2"></i> Ateliers
                    </a>
                    <a href="{{ route('admin.equipe.index') }}" class="nav-item {{ request()->routeIs('admin.equipe.*') ? 'active' : '' }}">
                        <i class="fas fa-users mr-2"></i> Mon équipe
                    </a>
                    <a href="{{ route('admin.entreprises.infos') }}" class="nav-item {{ request()->routeIs('admin.entreprises.infos') ? 'active' : '' }}">
                        <i class="fas fa-building-user mr-2"></i> Infos entreprise
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-chart-line mr-2"></i> Analytics
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-gear mr-2"></i> Paramètres
                    </a>
                @endif
            @endauth
        </div>
        
        <!-- Dark/Light Mode Toggle -->
        <div class="ml-auto flex items-center">
            <button id="theme-toggle" 
                    class="theme-toggle-btn"
                    aria-label="Toggle dark mode"
                    title="Toggle dark mode">
                <span class="theme-icon sun-icon cursor-pointer" onclick="toggleTheme(event)">
                    <i class="fas fa-sun text-yellow-500"></i>
                </span>
                <span class="theme-icon moon-icon cursor-pointer" onclick="toggleTheme(event)">
                    <i class="fas fa-moon text-indigo-400"></i>
                </span>
                <span class="sr-only">Toggle theme</span>
            </button>
        </div>
    </div>
</nav>

<script>
// Global theme toggle function for direct icon clicks
function toggleTheme(event) {
    event.stopPropagation(); // Prevent button click event
    
    const html = document.documentElement;
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.querySelector('.sun-icon');
    const moonIcon = document.querySelector('.moon-icon');
    
    // Determine current theme
    const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Remove old theme class
    html.classList.remove(currentTheme);
    // Add new theme class
    html.classList.add(newTheme);
    
    // Save preference
    localStorage.setItem('theme', newTheme);
    
    // Update button state with smooth transition
    updateThemeButton(newTheme);
    
    // Add ripple effect
    createRippleEffect(themeToggle);
}

document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.querySelector('.sun-icon');
    const moonIcon = document.querySelector('.moon-icon');
    
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.classList.remove('dark', 'light');
    html.classList.add(savedTheme);
    
    // Update button state
    updateThemeButton(savedTheme);
    
    // Theme toggle functionality for button background click
    themeToggle.addEventListener('click', function() {
        const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Remove old theme class
        html.classList.remove(currentTheme);
        // Add new theme class
        html.classList.add(newTheme);
        
        // Save preference
        localStorage.setItem('theme', newTheme);
        
        // Update button state with smooth transition
        updateThemeButton(newTheme);
        
        // Add ripple effect
        createRippleEffect(themeToggle);
    });
    
    function updateThemeButton(theme) {
        if (theme === 'dark') {
            sunIcon.style.opacity = '0';
            sunIcon.style.transform = 'scale(0.5)';
            moonIcon.style.opacity = '1';
            moonIcon.style.transform = 'scale(1)';
            themeToggle.style.borderColor = 'rgba(99, 102, 241, 0.5)';
            themeToggle.style.background = 'linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(147, 51, 234, 0.1))';
        } else {
            sunIcon.style.opacity = '1';
            sunIcon.style.transform = 'scale(1)';
            moonIcon.style.opacity = '0';
            moonIcon.style.transform = 'scale(0.5)';
            themeToggle.style.borderColor = 'rgba(245, 158, 11, 0.5)';
            themeToggle.style.background = 'linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.1))';
        }
    }
    
    function createRippleEffect(element) {
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;
        
        const ripple = document.createElement('span');
        ripple.style.position = 'fixed';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.6)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.6s linear';
        ripple.style.left = `${x - size / 2}px`;
        ripple.style.top = `${y - size / 2}px`;
        ripple.style.width = `${size}px`;
        ripple.style.height = `${size}px`;
        ripple.style.zIndex = '9999';
        ripple.style.pointerEvents = 'none';
        
        document.body.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
.theme-icon {
    @apply absolute transition-all duration-500 ease-in-out;
}

.sun-icon {
    @apply text-yellow-500;
}

.moon-icon {
    @apply text-indigo-400;
}

/* Animation for smooth icon transitions */
@keyframes themeTransition {
    from { transform: scale(0.5); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Enhanced ripple effect styles */
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Additional theme-specific enhancements */
html.light .theme-toggle-btn {
    @apply border-orange-300/50 bg-gradient-to-br from-orange-50 to-yellow-50 hover:from-orange-100 hover:to-yellow-100;
}

html.dark .theme-toggle-btn {
    @apply border-indigo-500/50 bg-gradient-to-br from-indigo-900/50 to-purple-900/50 hover:from-indigo-800/50 hover:to-purple-800/50;
}

/* Accessibility improvements */
.theme-toggle-btn:focus {
    @apply outline-none ring-4 ring-offset-2;
}

html.light .theme-toggle-btn:focus {
    @apply ring-orange-400/50;
}

html.dark .theme-toggle-btn:focus {
    @apply ring-indigo-400/50;
}
</style>
