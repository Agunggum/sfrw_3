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
        
        // Use a small delay to ensure the browser registers width: 0% before starting transition
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

        // POST handling
        isLoading = true;
        loadingStartTime = Date.now();
        startProgress();

        // Animasi Loading pada tombol
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
            form.submit(); // Fallback
        } finally {
            isLoading = false;
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
        // 1. Cek apakah sedang loading (dengan fail-safe 20 detik)
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
        
        // 2. Dapatkan elemen <a> terdekat
        const link = e.target.closest('a');
        if (!link) return;

        // 3. Abaikan jika klik dengan tombol selain tombol utama (kiri) atau dengan tombol modifier
        if (e.button !== 0 || e.ctrlKey || e.shiftKey || e.metaKey || e.altKey) return;

        // 4. Abaikan jika memiliki atribut download atau target="_blank"
        if (link.hasAttribute('download') || link.getAttribute('target') === '_blank') return;

        // 5. Dapatkan URL link
        const href = link.href;
        if (!href) return;

        // 6. Parsing URL
        let url;
        try {
            url = new URL(href);
        } catch (err) {
            return;
        }

        // 7. Abaikan jika link eksternal (host berbeda)
        if (url.origin !== window.location.origin) return;

        // 8. Abaikan jika protokol bukan http/https (misal mailto:, tel:)
        if (!['http:', 'https:'].includes(url.protocol)) return;

        // 9. Abaikan jika link anchor pada halaman yang sama
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

        // 10. Abaikan jika secara eksplisit dilarang melalui data-spa="false" atau data-spa-ignore
        if (link.dataset.spa === 'false' || link.hasAttribute('data-spa-ignore')) return;

        // 11. Cegah reload dan navigasi via SPA
        e.preventDefault();
        navigateTo(href);
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
        loadingStartTime = Date.now();
        container.style.opacity = '0.5';
        startProgress();

        // Timeout 15 detik untuk koneksi lambat
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
                // Jika ada redirect, kita hentikan loading saat ini dan mulai pemuatan baru
                isLoading = false; 
                // Kita tidak memanggil stopProgress di sini agar bar tetap terlihat jalan ke arah baru
                return await loadContent(redirectUrl, true);
            }
        } catch (error) {
            console.error('SPA load failed:', error);
            if (error.name === 'AbortError') {
                console.warn('SPA request timeout, falling back to full reload');
            }
            window.location.href = url;
        } finally {
            // Hanya jalankan pembersihan jika ini adalah pemanggilan terakhir (tidak ada redirect aktif)
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
        
        // 1. Cek perubahan Layout (Public vs Dashboard)
        const currentLayout = document.documentElement.getAttribute('data-spa-layout');
        const newLayout = response.headers.get('X-SPA-Layout');
        
        if (currentLayout && newLayout && currentLayout !== newLayout) {
            console.warn(`SPA: Layout change detected (${currentLayout} -> ${newLayout}), performing full reload.`);
            window.location.href = url;
            return;
        }

        // 2. Cek header redirect dari server
        const spaRedirect = response.headers.get('X-SPA-Redirect');
        if (spaRedirect) {
            return spaRedirect;
        }

        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        const title = response.headers.get('X-Page-Title');

        // Update content
        container.innerHTML = html;

        // Update title
        if (title) document.title = title;

        // Update URL
        if (pushState && url !== window.location.href) {
            window.history.pushState({}, title || '', url);
        }

        // Re-initialize assets (CSS) and scripts
        reinitAssets();
        reinitScripts();
        window.scrollTo(0, 0);
        
        return null;
    };

    const reinitAssets = () => {
        const container = document.getElementById(contentId);
        if (!container) return;

        // Pindahkan <link> dan <style> dari konten ke <head> agar ter-render dengan benar
        // dan tidak terduplikasi saat navigasi selanjutnya
        const assets = container.querySelectorAll('link[rel="stylesheet"], style');
        const head = document.head;

        assets.forEach(asset => {
            const isLink = asset.tagName === 'LINK';
            const assetId = isLink ? asset.href : asset.textContent.substring(0, 100);
            
            // Cek apakah asset sudah ada di head
            let exists = false;
            if (isLink) {
                exists = !!head.querySelector(`link[href="${asset.href}"]`);
            } else {
                // Untuk inline style, kita bisa gunakan hash atau id jika ada
                // Namun untuk kesederhanaan, kita biarkan saja jika tidak ada ID khusus
                if (asset.id) exists = !!head.querySelector(`#${asset.id}`);
            }

            if (!exists) {
                const newAsset = asset.cloneNode(true);
                head.appendChild(newAsset);
            }
            
            // Hapus dari container agar tidak dieksekusi ulang/ganda oleh browser
            asset.remove();
        });
    };

    const reinitScripts = () => {
        // Re-init Theme Toggle (Dropdown version)
        initThemeSwitcher();

        // Re-init data- attributes components (Bootstrap, Avatars, etc.)
        reinitDataComponents();

        // Re-init any other components (Shorten, etc.)
        if (typeof $ !== 'undefined' && $.fn.shorten) {
            $('.comment').shorten();
        }
        
        // Execute scripts inside the new content
        const container = document.getElementById(contentId);
        if (!container) return;

        const scripts = container.querySelectorAll('script');
        
        // Kita gunakan array untuk memproses secara berurutan
        const scriptArray = Array.from(scripts);
        
        scriptArray.forEach(oldScript => {
            try {
                const newScript = document.createElement('script');
                
                // Salin semua atribut
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                
                // Handle isi script
                if (oldScript.src) {
                    // Script eksternal
                    // Tambahkan cache buster untuk module agar re-run di SPA
                    if (oldScript.type === 'module') {
                        const url = new URL(oldScript.src, window.location.href);
                        url.searchParams.set('spa_t', Date.now());
                        newScript.src = url.href;
                    }
                } else if (oldScript.type === 'module') {
                    // Module script inline (lit-html, dll)
                    const scriptContent = oldScript.innerHTML;
                    const blob = new Blob([scriptContent], { type: 'application/javascript' });
                    newScript.src = URL.createObjectURL(blob);
                } else {
                    // Inline script biasa
                    newScript.textContent = oldScript.textContent;
                }
                
                // Ganti script lama dengan yang baru untuk memicu eksekusi
                if (oldScript.parentNode) {
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                }
            } catch (err) {
                console.error('Error re-initializing script:', err, oldScript);
            }
        });

        // Trigger Custom Event for components that need to know content has changed
        // Berikan delay sedikit agar script (terutama module) punya waktu untuk mulai loading
        setTimeout(() => {
            document.dispatchEvent(new CustomEvent('spa:content-loaded', {
                detail: { url: window.location.href, data: window.pageData || {} }
            }));
        }, 10);
    };

    const reinitDataComponents = () => {
        // 1. Re-init Bootstrap Dropdowns, Tooltips, Popovers
        if (typeof bootstrap !== 'undefined') {
            // Dropdowns
            const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            dropdowns.forEach(el => {
                // Bersihkan instance lama jika ada untuk menghindari memory leak atau double init
                const instance = bootstrap.Dropdown.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Dropdown(el);
            });

            // Tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(el => {
                const instance = bootstrap.Tooltip.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Tooltip(el);
            });

            // Popovers
            const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
            popovers.forEach(el => {
                const instance = bootstrap.Popover.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Popover(el);
            });
        }

        // 2. Re-init User Avatars (data-name)
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

        // 3. Re-init Sidebar Toggler
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebarToggle && sidebarOverlay) {
            // Remove old listeners to prevent multiple execution
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

        // 4. Re-init Translation (data-lang-id)
        if (typeof window.changeLanguage === 'function') {
            const currentLang = localStorage.getItem('user_language') || 'id';
            window.changeLanguage(currentLang);
        }
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