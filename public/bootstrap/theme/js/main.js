/**
 * SPA Navigation Manager
 */
const SPANavigator = (() => {
    const contentId = 'spa-content';
    let progressBar = null;
    let progressTimeout = null;
    let isLoading = false;
    let loadingStartTime = 0;
    
    const init = () => {
        createProgressBar();
        document.addEventListener('click', handleLinkClick);
        document.addEventListener('submit', handleFormSubmit);
        window.addEventListener('popstate', handlePopState);
        setupGlobalPasswordToggle(); // Event Delegation Global untuk Password Toggle
    };

    /**
     * Helper untuk memvalidasi apakah URL aman untuk SPA
     */
    const shouldUseSPA = (href) => {
        if (!href) return false;
        try {
            const url = new URL(href, window.location.href);
            if (url.origin !== window.location.origin) return false;
            if (!['http:', 'https:'].includes(url.protocol)) return false;
            if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
                return false;
            }
            return true;
        } catch (e) {
            return false;
        }
    };

    /**
     * Event Delegation Global untuk Toggle Password.
     * Dipasang sekali pada level `document`, sehingga SELALU BEKERJA 
     * meskipun elemen password berganti/dimuat ulang via SPA.
     */
    const setupGlobalPasswordToggle = () => {
        document.addEventListener('click', (e) => {
            // Memicu jika elemen yang diklik (atau elemen di dalamnya) cocok dengan selector toggle
            const toggleBtn = e.target.closest('#toggle-password, .toggle-password, [data-toggle="password"]');
            if (!toggleBtn) return;

            e.preventDefault();

            // 1. Cari target input berdasarkan atribut data-target (jika ada)
            const targetSelector = toggleBtn.getAttribute('data-target');
            let passwordField = targetSelector ? document.querySelector(targetSelector) : null;

            // 2. Jika tidak ada data-target, cari berdasarkan ID default (#password-field)
            if (!passwordField) {
                passwordField = document.querySelector('#password-field');
            }

            // 3. Jika masih tidak ketemu, cari input password terdekat dalam satu container/form
            if (!passwordField) {
                const container = toggleBtn.closest('.input-group, form, div');
                if (container) {
                    passwordField = container.querySelector('input[type="password"], input[type="text"]');
                }
            }

            // Eksekusi perubahan tipe input & icon
            if (passwordField) {
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';

                // Ubah Icon (Mendukung Bootstrap Icons & FontAwesome)
                const icon = toggleBtn.querySelector('i') || toggleBtn;
                if (icon) {
                    icon.classList.toggle('bi-eye', isPassword);
                    icon.classList.toggle('bi-eye-slash', !isPassword);
                }
            }
        });
    };

    const createProgressBar = () => {
        let existing = document.getElementById('nprogress');
        if (existing) {
            progressBar = existing;
            return;
        }
        progressBar = document.createElement('div');
        progressBar.id = 'nprogress';
        progressBar.innerHTML = '<div class="bar"></div>';
        document.body.appendChild(progressBar);
    };

    const startProgress = () => {
        if (!progressBar) createProgressBar();
        const bar = progressBar.querySelector('.bar');
        if (!bar) return;
        
        if (progressTimeout) {
            clearTimeout(progressTimeout);
            progressTimeout = null;
        }

        progressBar.style.opacity = '1';
        bar.style.width = '0%';
        bar.style.transition = 'width 0.3s ease';
        
        requestAnimationFrame(() => {
            setTimeout(() => { if (bar) bar.style.width = '30%'; }, 50);
            setTimeout(() => { if (bar) bar.style.width = '70%'; }, 400);
        });
    };

    const stopProgress = () => {
        if (!progressBar) return;
        const bar = progressBar.querySelector('.bar');
        if (!bar) return;

        bar.style.width = '100%';
        
        if (progressTimeout) clearTimeout(progressTimeout);
        
        progressTimeout = setTimeout(() => {
            if (progressBar && bar) {
                progressBar.style.opacity = '0';
                setTimeout(() => {
                    bar.style.transition = 'none';
                    bar.style.width = '0%';
                }, 200);
            }
            progressTimeout = null;
        }, 300);
    };

    const handleFormSubmit = async (e) => {
        const form = e.target.closest('form');
        if (!form || form.getAttribute('target') === '_blank' || isLoading) {
            if (isLoading) e.preventDefault();
            return;
        }

        const submitBtn = form.querySelector('[type="submit"]');
        const method = form.getAttribute('method')?.toUpperCase() || 'GET';
        
        e.preventDefault();
        
        const url = form.getAttribute('action') || window.location.href;

        if (method === 'GET') {
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

        isLoading = true;
        loadingStartTime = Date.now();
        startProgress();

        let originalBtnHtml = '';
        if (submitBtn) {
            originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...`;
        }

        const options = {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        try {
            const response = await fetch(url, options);
            const redirectUrl = await handleResponse(response, url);
            if (redirectUrl) {
                isLoading = false;
                return await loadContent(redirectUrl, true);
            }
        } catch (error) {
            console.error('SPA form submit failed:', error);
            form.submit();
        } finally {
            isLoading = false;
            stopProgress();
            if (submitBtn && document.body.contains(submitBtn)) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }
    };

    const handleLinkClick = (e) => {
        if (isLoading) {
            const now = Date.now();
            if (now - loadingStartTime > 20000) {
                console.warn('SPA stuck detected, forcing reset...');
                isLoading = false;
            } else {
                e.preventDefault();
                return;
            }
        }
        
        const link = e.target.closest('a');
        if (!link) return;

        if (e.button !== 0 || e.ctrlKey || e.shiftKey || e.metaKey || e.altKey) return;
        if (link.hasAttribute('download') || link.getAttribute('target') === '_blank') return;

        const href = link.href;
        if (!shouldUseSPA(href)) return;

        if (link.dataset.spa === 'false' || link.hasAttribute('data-spa-ignore')) return;

        e.preventDefault();
        navigateTo(href);
    };

    const handlePopState = (e) => {
        loadContent(window.location.pathname + window.location.search, false);
    };

    const navigateTo = (url) => {
        if (!shouldUseSPA(url)) {
            window.location.href = url;
            return;
        }
        loadContent(url, true);
    };

    const loadContent = async (url, pushState = true) => {
        if (isLoading) return;
        
        const container = document.getElementById(contentId);
        if (!container) return;

        isLoading = true;
        loadingStartTime = Date.now();
        container.style.opacity = '0.5';
        startProgress();

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 15000);

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            clearTimeout(timeoutId);
            const redirectUrl = await handleResponse(response, url, pushState);
            
            if (redirectUrl) {
                isLoading = false; 
                return await loadContent(redirectUrl, true);
            }
        } catch (error) {
            console.error('SPA load failed:', error);
            if (error.name === 'AbortError') {
                console.warn('SPA request timeout, falling back to full reload');
            }
            window.location.href = url;
        } finally {
            if (isLoading) {
                isLoading = false;
                stopProgress();
                const finalContainer = document.getElementById(contentId);
                if (finalContainer) finalContainer.style.opacity = '1';
            }
        }
    };

    const handleResponse = async (response, url, pushState = true) => {
        const container = document.getElementById(contentId);
        if (!container) return;
        
        const currentLayout = document.documentElement.getAttribute('data-spa-layout');
        const newLayout = response.headers.get('X-SPA-Layout');
        
        if (currentLayout && newLayout && currentLayout !== newLayout) {
            console.warn(`SPA: Layout change detected (${currentLayout} -> ${newLayout}), performing full reload.`);
            window.location.href = url;
            return;
        }

        const spaRedirect = response.headers.get('X-SPA-Redirect');
        if (spaRedirect) {
            return spaRedirect;
        }

        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        const title = response.headers.get('X-Page-Title');

        cleanUpUI();

        container.innerHTML = html;
        if (title) document.title = title;

        if (pushState && url !== window.location.href) {
            window.history.pushState({}, title || '', url);
        }

        reinitAssets();
        reinitScripts();
        window.scrollTo(0, 0);
        
        return null;
    };

    const cleanUpUI = () => {
        document.querySelectorAll('.modal-backdrop, .offcanvas-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open', 'overflow-hidden');
        document.body.style.removeProperty('padding-right');
    };

    const reinitAssets = () => {
        const container = document.getElementById(contentId);
        if (!container) return;

        const assets = container.querySelectorAll('link[rel="stylesheet"], style');
        const head = document.head;

        assets.forEach(asset => {
            const isLink = asset.tagName === 'LINK';
            let exists = false;
            if (isLink) {
                exists = !!head.querySelector(`link[href="${asset.href}"]`);
            } else {
                if (asset.id) exists = !!head.querySelector(`#${asset.id}`);
            }

            if (!exists) {
                const newAsset = asset.cloneNode(true);
                head.appendChild(newAsset);
            }
            asset.remove();
        });
    };

    const reinitScripts = () => {
        initThemeSwitcher();

        const container = document.getElementById(contentId);
        if (!container) return;

        const scripts = container.querySelectorAll('script');
        const scriptArray = Array.from(scripts);
        
        scriptArray.forEach(oldScript => {
            try {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                
                if (oldScript.src) {
                    if (oldScript.type === 'module') {
                        const url = new URL(oldScript.src, window.location.href);
                        url.searchParams.set('spa_t', Date.now());
                        newScript.src = url.href;
                    }
                } else if (oldScript.type === 'module') {
                    const scriptContent = oldScript.innerHTML;
                    const blob = new Blob([scriptContent], { type: 'application/javascript' });
                    newScript.src = URL.createObjectURL(blob);
                } else {
                    newScript.textContent = oldScript.textContent;
                }
                
                if (oldScript.parentNode) {
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                }
            } catch (err) {
                console.error('Error re-initializing script:', err, oldScript);
            }
        });

        setTimeout(() => {
            reinitDataComponents();
            
            document.dispatchEvent(new CustomEvent('spa:content-loaded', {
                detail: { url: window.location.href, data: window.pageData || {} }
            }));
        }, 20);
    };

    const reinitDataComponents = () => {
        if (typeof bootstrap !== 'undefined') {
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
                const instance = bootstrap.Dropdown.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Dropdown(el);
            });

            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                const instance = bootstrap.Tooltip.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Tooltip(el);
            });

            document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
                const instance = bootstrap.Popover.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Popover(el);
            });
        }

        const avatarEls = document.querySelectorAll('#userAvatar, .user-avatar');
        avatarEls.forEach(avatarEl => {
            const fullName = avatarEl.getAttribute('data-name') || 'User';
            const words = fullName.trim().split(' ');
            let initials = '';
            if (words.length > 0 && words[0] !== '') {
                initials += words[0][0];
                if (words.length > 1) initials += words[words.length - 1][0];
            } else {
                initials = 'U';
            }
            avatarEl.setAttribute('data-initials', initials);

            let hash = 0;
            for (let i = 0; i < fullName.length; i++) {
                hash = fullName.charCodeAt(i) + ((hash << 5) - hash);
            }
            const h = Math.abs(hash % 360);
            avatarEl.style.backgroundColor = `hsl(${h}, 65%, 45%)`;
        });

        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebarToggle && sidebarOverlay) {
            const newToggle = sidebarToggle.cloneNode(true);
            sidebarToggle.parentNode.replaceChild(newToggle, sidebarToggle);
            
            const newOverlay = sidebarOverlay.cloneNode(true);
            sidebarOverlay.parentNode.replaceChild(newOverlay, sidebarOverlay);

            const toggleSidebar = (e) => {
                e.preventDefault();
                document.body.classList.toggle('sb-toggled');
            };

            newToggle.addEventListener('click', toggleSidebar);
            newOverlay.addEventListener('click', toggleSidebar);
        }

        if (typeof window.changeLanguage === 'function') {
            const currentLang = localStorage.getItem('user_language') || 'id';
            window.changeLanguage(currentLang);
        }

        if (typeof $ !== 'undefined' && $.fn.shorten) {
            $('.comment').shorten();
        }
    };

    const getStoredTheme = () => localStorage.getItem('theme');
    const setStoredTheme = theme => localStorage.setItem('theme', theme);

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme) return storedTheme;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const setTheme = theme => {
        if (theme === 'auto') {
            document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
        showActiveTheme(theme);
    };

    const showActiveTheme = (theme, focus = false) => {
        const themeIconActive = document.querySelector('#themeIconActive') || document.querySelector('#bd-theme .theme-icon-active');
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        
        if (!btnToActive) return;

        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active');
            element.setAttribute('aria-pressed', 'false');
            const checkIcon = element.querySelector('.bi-check2');
            if (checkIcon) checkIcon.classList.add('d-none');
        });

        btnToActive.classList.add('active');
        btnToActive.setAttribute('aria-pressed', 'true');
        
        const activeCheckIcon = btnToActive.querySelector('.bi-check2');
        if (activeCheckIcon) activeCheckIcon.classList.remove('d-none');
        
        if (themeIconActive) {
            const iconEl = btnToActive.querySelector('i');
            if (iconEl) {
                themeIconActive.className = iconEl.className.replace('me-2', '').replace('opacity-50', '').replace('theme-icon', 'theme-icon-active').trim();
                if(theme === 'dark') {
                    themeIconActive.classList.add('text-warning');
                } else {
                    themeIconActive.classList.remove('text-warning');
                }
            }
        }
    };

    const initThemeSwitcher = () => {
        const theme = getPreferredTheme();
        setTheme(theme);
        showActiveTheme(theme);
    };

    const setupThemeEventListeners = () => {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        document.addEventListener('click', e => {
            const toggle = e.target.closest('[data-bs-theme-value]');
            if (toggle) {
                const theme = toggle.getAttribute('data-bs-theme-value');
                setStoredTheme(theme);
                setTheme(theme);
                showActiveTheme(theme, true);
            }
        });
    };

    return { init, navigateTo, initThemeSwitcher, setupThemeEventListeners };
})();

// Start SPA Navigation
SPANavigator.init();
SPANavigator.initThemeSwitcher();
SPANavigator.setupThemeEventListeners();

// jQuery Shorten Plugin
(function($) {
    $.fn.shorten = function(settings) {
        "use strict";

        var config = {
            showChars: 300,
            minHideChars: 30,
            ellipsesText: "...",
            moreText: "<i class='bi bi-angles-down'></i> Selengkapnya",
            lessText: "<i class='bi bi-angles-up'></i> Sembunyikan",
            onLess: function() {},
            onMore: function() {},
            force: false
        };

        if (settings) $.extend(config, settings);

        if ($(this).data('jquery.shorten') && !config.force) return false;
        $(this).data('jquery.shorten', true);

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