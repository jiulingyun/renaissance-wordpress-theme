// Language switching functionality
class LanguageSwitcher {
    constructor() {
        this.currentLang = localStorage.getItem('language') || 'en';
        this.loadTranslations();
        this.init();
    }

    loadTranslations() {
        // Set translations from globally loaded files
        this.translations = {
            en: window.enTranslations || {},
            zh: window.zhTranslations || {},
            fr: window.frTranslations || {}
        };
        
        // Fallback if translations are not loaded
        if (!window.enTranslations || !window.zhTranslations || !window.frTranslations) {
            console.warn('Some translation files may not be loaded properly');
            // Basic fallback
            this.translations = {
                en: this.translations.en || { 'nav-language': 'EN' },
                zh: this.translations.zh || { 'nav-language': '中文' },
                fr: this.translations.fr || { 'nav-language': 'FR' }
            };
        }
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        } else {
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        // Language dropdown event listeners
        const languageDropdown = document.getElementById('languageDropdown');
        const languageItems = document.querySelectorAll('[data-lang]');

        if (languageDropdown) {
            languageItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const selectedLang = item.getAttribute('data-lang');
                    this.switchLanguage(selectedLang);
                });
            });
        }

        // Initial translation
        this.updateLanguageDisplay();
        this.translatePage();
    }

    switchLanguage(lang) {
        if (this.translations[lang]) {
            this.currentLang = lang;
            localStorage.setItem('language', lang);
            this.updateLanguageDisplay();
            this.translatePage();
        }
    }

    updateLanguageDisplay() {
        const languageDropdown = document.getElementById('languageDropdown');
        if (languageDropdown && this.translations[this.currentLang]) {
            const langText = this.translations[this.currentLang]['nav-language'] || this.currentLang.toUpperCase();
            languageDropdown.innerHTML = `<i class="bi bi-translate"></i> ${langText}`;
        }

        // Update active state in dropdown
        document.querySelectorAll('[data-lang]').forEach(item => {
            const lang = item.getAttribute('data-lang');
            if (lang === this.currentLang) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    translatePage() {
        const currentTranslations = this.translations[this.currentLang];
        
        if (!currentTranslations) {
            console.warn(`No translations found for language: ${this.currentLang}`);
            return;
        }
        
        // Translate elements with data-translate attribute
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (currentTranslations[key]) {
                if (element.tagName === 'INPUT' && element.type === 'email') {
                    element.placeholder = currentTranslations[key];
                } else if (element.hasAttribute('data-translate-placeholder')) {
                    element.placeholder = currentTranslations[key];
                } else {
                    element.textContent = currentTranslations[key];
                }
            }
        });

        // Special handling for specific elements
        this.translateSpecialElements(currentTranslations);
    }

    translateSpecialElements(translations) {
        // Update navigation brand
        const navBrand = document.querySelector('.navbar-brand');
        if (navBrand && translations['nav-language']) {
            // Keep the logo, just update any text if needed
        }

        // 注释掉页面标题修改，让 WordPress 控制页面标题
        // if (translations['hero-title-1']) {
        //     document.title = 'Renaissance Technologies';
        // }

        // Handle placeholder translations
        document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
            const key = element.getAttribute('data-translate-placeholder');
            if (translations[key]) {
                element.placeholder = translations[key];
            }
        });
    }

    // Public method to get current language
    getCurrentLanguage() {
        return this.currentLang;
    }

    // Public method to get translation
    getTranslation(key) {
        return this.translations[this.currentLang][key] || key;
    }
}

// Initialize language switcher when DOM is ready
let languageSwitcher;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        languageSwitcher = new LanguageSwitcher();
    });
} else {
    languageSwitcher = new LanguageSwitcher();
}