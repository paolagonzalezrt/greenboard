/**
 * Theme and Language Manager
 * Manages theme switching and language preferences with localStorage
 */

class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.language = localStorage.getItem('language') || 'en';
        this.init();
    }

    init() {
        this.applyTheme();
        this.applyLanguage();
        this.setupEventListeners();
    }

    applyTheme() {
        const html = document.documentElement;
        html.classList.remove('light', 'dark');
        html.classList.add(this.theme);
        this.updateThemeIcon();
    }

    applyLanguage() {
        document.documentElement.setAttribute('lang', this.language);
    }

    updateThemeIcon() {
        const themeIcon = document.querySelector('#theme-toggle .material-symbols-outlined');
        if (themeIcon) {
            themeIcon.textContent = this.theme === 'dark' ? 'light_mode' : 'dark_mode';
        }
    }

    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();
    }

    toggleLanguage() {
        this.language = this.language === 'en' ? 'es' : 'en';
        localStorage.setItem('language', this.language);
        this.applyLanguage();
        
        // Visual feedback
        const langIcon = document.querySelector('#language-toggle .material-symbols-outlined');
        if (langIcon) {
            const originalIcon = langIcon.textContent;
            langIcon.textContent = 'check_circle';
            setTimeout(() => {
                langIcon.textContent = originalIcon;
            }, 1000);
        }

        console.log(`Language changed to: ${this.language === 'en' ? 'English' : 'Español'}`);
        
        // Uncomment to reload page with language parameter
        // window.location.href = `${window.location.pathname}?lang=${this.language}`;
    }

    setupEventListeners() {
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('language-toggle');

        if (themeBtn) {
            themeBtn.addEventListener('click', () => this.toggleTheme());
        }

        if (langBtn) {
            langBtn.addEventListener('click', () => this.toggleLanguage());
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new ThemeManager());
} else {
    new ThemeManager();
}

export default ThemeManager;
