/**
 * SPA Navigation Manager
 */
const SPANavigator = (() => {
    const contentId = 'spa-content';
    let progressBar = null;
    let isLoading = false;
    
    const init = () => {
        createProgressBar();
        document.addEventListener('click', handleLinkClick);
        document.addEventListener('submit', handleFormSubmit);
        window.addEventListener('popstate', handlePopState);
    };

    const createProgressBar = () => {
        if (document.getElementById('nprogress')) return;
        progressBar = document.createElement('div');
        progressBar.id = 'nprogress';
        progressBar.innerHTML = '<div class="bar"></div>';
        document.body.appendChild(progressBar);
    };

    const startProgress = () => {
        const bar = progressBar.querySelector('.bar');
        bar.style.width = '0%';
        bar.style.transition = 'width 0.3s ease';
        setTimeout(() => bar.style.width = '30%', 50);
        setTimeout(() => bar.style.width = '70%', 400);
    };

    const stopProgress = () => {
        const bar = progressBar.querySelector('.bar');
        bar.style.width = '100%';
        setTimeout(() => {
            bar.style.width = '0%';
            bar.style.transition = 'none';
        }, 300);
    };

    const handleFormSubmit = async (e) => {
        const form = e.target.closest('form');
        if (!form || form.getAttribute('target') === '_blank') {
            return;
        }

        const submitBtn = form.querySelector('[type="submit"]');
        const method = form.getAttribute('method')?.toUpperCase() || 'GET';
        
        // Animasi Loading pada tombol
        let originalBtnHtml = '';
        if (submitBtn) {
            originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...`;
        }

        e.preventDefault();
        startProgress();
        
        const url = form.getAttribute('action') || window.location.href;
        const options = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (method === 'POST') {
            options.method = 'POST';
            options.body = new FormData(form);
        } else {
            // Handle GET form
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const getUrl = url.includes('?') ? `${url}&${params}` : `${url}?${params}`;
            
            try {
                await loadContent(getUrl, true);
                return;
            } catch (error) {
                console.error('SPA GET form failed:', error);
                window.location.href = getUrl;
                return;
            }
        }

        try {
            const response = await fetch(url, options);
            await handleResponse(response, url);
        } catch (error) {
            console.error('SPA form submit failed:', error);
            form.submit(); // Fallback
        } finally {
            stopProgress();
            if (submitBtn && !document.body.contains(submitBtn)) {
                // Button might have been replaced by SPA content, do nothing
            } else if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }
    };

    const handleLinkClick = (e) => {
        if (isLoading) {
            e.preventDefault();
            return;
        }
        
        const link = e.target.closest('a');
        if (!link) return;

        const url = link.getAttribute('href');
        if (!url || url.startsWith('http') || url.startsWith('#') || url.startsWith('javascript:') || link.getAttribute('target') === '_blank') {
            return;
        }

        e.preventDefault();
        navigateTo(url);
    };

    const handlePopState = (e) => {
        loadContent(window.location.pathname + window.location.search, false);
    };

    const navigateTo = (url) => {
        loadContent(url, true);
    };

    const loadContent = async (url, pushState = true) => {
        if (isLoading) return;
        
        const container = document.getElementById(contentId);
        if (!container) return;

        isLoading = true;
        container.style.opacity = '0.5';
        startProgress();

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            handleResponse(response, url, pushState);
        } catch (error) {
            console.error('SPA load failed:', error);
            window.location.href = url;
        } finally {
            stopProgress();
        }
    };

    const handleResponse = async (response, url, pushState = true) => {
        const container = document.getElementById(contentId);
        
        // Cek header redirect dari server
        const spaRedirect = response.headers.get('X-SPA-Redirect');
        if (spaRedirect) {
            navigateTo(spaRedirect);
            return;
        }

        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        const title = response.headers.get('X-Page-Title');

        // Update content
        container.innerHTML = html;
        container.style.opacity = '1';

        // Update title
        if (title) document.title = title;

        // Update URL
        if (pushState && url !== window.location.href) {
            window.history.pushState({}, title || '', url);
        }

        // Re-initialize scripts
        reinitScripts();
        window.scrollTo(0, 0);
    };

    const reinitScripts = () => {
        // Re-init Theme Toggle (Dropdown version)
        initThemeSwitcher();

        // Re-init any other components (Shorten, etc.)
        $('.comment').shorten();
        
        // Execute scripts inside the new content
        const scripts = document.getElementById(contentId).querySelectorAll('script');
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            
            // Handle Module Scripts (for Lit-HTML components)
            if (oldScript.type === 'module') {
                const scriptContent = oldScript.innerHTML;
                const blob = new Blob([scriptContent], { type: 'application/javascript' });
                newScript.src = URL.createObjectURL(blob);
            } else {
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            }
            
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        // Trigger Custom Event for Lit-HTML auto-render
        document.dispatchEvent(new CustomEvent('spa:content-loaded', {
            detail: { url: window.location.href, data: window.pageData || {} }
        }));
    };

    const getStoredTheme = () => localStorage.getItem('theme')
    const setStoredTheme = theme => localStorage.setItem('theme', theme)

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme()
        if (storedTheme) {
            return storedTheme
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    const setTheme = theme => {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme === 'auto' ? 'light' : theme)
        }
    }

    const showActiveTheme = (theme, focus = false) => {
        const themeSwitcher = document.querySelector('#bd-theme')
        if (!themeSwitcher) return

        const activeThemeIcon = themeSwitcher.querySelector('i')
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
        
        if (!btnToActive) return

        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active')
            element.setAttribute('aria-pressed', 'false')
            const checkIcon = element.querySelector('.bi-check2')
            if (checkIcon) checkIcon.classList.add('d-none')
        })

        btnToActive.classList.add('active')
        btnToActive.setAttribute('aria-pressed', 'true')
        
        const activeCheckIcon = btnToActive.querySelector('.bi-check2')
        if (activeCheckIcon) activeCheckIcon.classList.remove('d-none')
        
        // Update main button icon
        const iconClasses = btnToActive.querySelector('.theme-icon').classList.value
        activeThemeIcon.className = iconClasses.replace('me-2 opacity-50', 'me-2').replace('theme-icon', 'theme-icon-active')

        if (focus) {
            themeSwitcher.focus()
        }
    }

    const initThemeSwitcher = () => {
        const theme = getPreferredTheme()
        setTheme(theme)
        showActiveTheme(theme)
    }

    const setupThemeEventListeners = () => {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme()
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme())
            }
        })

        // Use event delegation for theme toggles to avoid multiple listeners during SPA navigation
        document.addEventListener('click', e => {
            const toggle = e.target.closest('[data-bs-theme-value]')
            if (toggle) {
                const theme = toggle.getAttribute('data-bs-theme-value')
                setStoredTheme(theme)
                setTheme(theme)
                showActiveTheme(theme, true)
            }
        })
    }

    return { init, navigateTo, initThemeSwitcher, setupThemeEventListeners };
})();

// Start SPA Navigation
SPANavigator.init();
SPANavigator.initThemeSwitcher();
SPANavigator.setupThemeEventListeners();

(function($) {
    $.fn.shorten = function(settings) {
        "use strict";

        var config = {
            showChars: 300,
            minHideChars: 30,
            ellipsesText: "...",
            // Menggunakan class Bootstrap yang adaptif terhadap Dark Mode
            moreText: "<i class='fa fa-angles-down'></i> Selengkapnya",
            lessText: "<i class='fa fa-angles-up'></i> Sembunyikan",
            onLess: function() {},
            onMore: function() {},
            force: false
        };

        if (settings) $.extend(config, settings);

        // Pencegahan inisialisasi ganda
        if ($(this).data('jquery.shorten') && !config.force) return false;
        $(this).data('jquery.shorten', true);

        // Gunakan delegasi event yang lebih efisien
        $(document).off("click", '.morelink').on("click", '.morelink', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $container = $this.closest('.shorten-container');
            var $shortContent = $container.find('.shortcontent');
            var $allContent = $container.find('.allcontent');

            if ($this.hasClass('less')) {
                $this.removeClass('less').html(config.moreText);
                $allContent.slideUp('fast', function() {
                    $shortContent.fadeIn();
                    config.onLess();
                });
            } else {
                $this.addClass('less').html(config.lessText);
                $shortContent.hide();
                $allContent.slideDown('fast', function() {
                    config.onMore();
                });
            }
        });

        return this.each(function() {
            var $this = $(this);
            var content = $this.html();
            var textContent = $this.text();

            if (textContent.length > config.showChars + config.minHideChars) {
                // Pemotongan sederhana yang aman untuk teks murni
                var visibleText = textContent.substring(0, config.showChars);
                
                var html = `
                    <div class="shorten-container">
                        <div class="shortcontent">${visibleText}<span class="ellip">${config.ellipsesText}</span></div>
                        <div class="allcontent" style="display:none">${content}</div>
                        <div class="mt-2">
                            <a href="#" class="morelink btn btn-sm btn-outline-secondary shadow-sm">${config.moreText}</a>
                        </div>
                    </div>`;

                $this.html(html);
            }
        });
    };
})(jQuery);

/**
 * Theme Helper Tools
 */
(() => {
    'use strict';

    // Toggle Password Helper
    const togglePassword = document.querySelector('#toggle-password');
    const passwordField = document.querySelector('#password-field');

    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }
        });
    }
})();