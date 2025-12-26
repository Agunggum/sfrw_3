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
 * Theme Manager & Helper Tools
 */
(() => {
    'use strict';

    const themeToggle = document.getElementById('darkModeToggle');
    const htmlElement = document.documentElement;

    const applyTheme = (theme) => {
        htmlElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeToggle) themeToggle.checked = (theme === 'dark');
        
        // Optimasi: Tambahkan transisi CSS secara dinamis agar mata nyaman
        if (!document.getElementById('theme-transition')) {
            const style = document.createElement('style');
            style.id = 'theme-transition';
            style.innerHTML = `*{transition: background-color 0.3s ease, color 0.3s ease !important;}`;
            document.head.appendChild(style);
        }
    };

    const getPreferredTheme = () => {
        const stored = localStorage.getItem('theme');
        if (stored) return stored;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // Inisialisasi Tema
    applyTheme(getPreferredTheme());

    if (themeToggle) {
        themeToggle.addEventListener('change', () => {
            applyTheme(themeToggle.checked ? 'dark' : 'light');
        });
    }

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