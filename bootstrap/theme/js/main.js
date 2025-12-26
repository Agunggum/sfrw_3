(function($) {
    $.fn.shorten = function(settings) {
        "use strict";

        var config = {
            showChars: 300,
            minHideChars: 30,
            ellipsesText: "...",
            moreText: "<i class='fa fa-angles-down'></i> Selengkapnya",
            lessText: "<i class='fa fa-angles-up'></i> Sembunyikan",
            onLess: function() {},
            onMore: function() {},
            force: false
        };

        if (settings) $.extend(config, settings);

        // Gunakan delegasi event sekali saja untuk efisiensi
        if (!$(document).data('shorten-event-initialized')) {
            $(document).on('click', '.morelink', function(e) {
                e.preventDefault();
                var $this = $(this);
                var $container = $this.closest('.shorten-container');
                var $shortContent = $container.find('.shortcontent');
                var $allContent = $container.find('.allcontent');

                if ($this.hasClass('less')) {
                    $this.removeClass('less').html(config.moreText);
                    $allContent.slideUp('fast', function() {
                        $shortContent.show();
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
            $(document).data('shorten-event-initialized', true);
        }

        return this.each(function() {
            var $el = $(this);
            if ($el.data('jquery.shorten') && !config.force) return;
            $el.data('jquery.shorten', true);

            var content = $el.html();
            var textLen = $el.text().length;

            if (textLen > config.showChars + config.minHideChars) {
                // Gunakan cara aman untuk memotong teks tanpa merusak HTML
                var visibleText = content.substring(0, config.showChars);
                
                // Perbaikan: Memastikan tag HTML yang terpotong tertutup otomatis
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = visibleText;
                var safeShortHtml = tempDiv.innerHTML;

                var html = `
                    <div class="shorten-container">
                        <div class="shortcontent">${safeShortHtml}<span class="ellip">${config.ellipsesText}</span></div>
                        <div class="allcontent" style="display:none">${content}</div>
                        <div class="mt-2">
                            <a href="#" class="morelink btn btn-outline-danger btn-sm float-right">${config.moreText}</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>`;

                $el.html(html);
            }
        });
    };
})(jQuery);

// --- Theme Toggle & Initialize ---
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('darkModeToggle');
    const htmlElement = document.documentElement;

    const applyTheme = (theme) => {
        theme === 'dark' ? htmlElement.setAttribute('data-bs-theme', 'dark') : htmlElement.removeAttribute('data-bs-theme');
        if(themeToggle) themeToggle.checked = (theme === 'dark');
    };

    const preferredTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    applyTheme(preferredTheme);

    if(themeToggle) {
        themeToggle.addEventListener('change', () => {
            const newTheme = themeToggle.checked ? 'dark' : 'light';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    // Initialize Plugins
    if ($.fn.DataTable) {
        $('.datatable').DataTable({ scrollX: true });
    }
    
    // Initialize Shorten
    $('.shorten').shorten();
});

// --- Password Toggle ---
const togglePassword = document.querySelector('#toggle-password');
const passwordField = document.querySelector('#password');

if (togglePassword && passwordField) {
    togglePassword.addEventListener('click', function (e) {
        // Mencegah form submit jika tombol di dalam form
        e.preventDefault(); 
        
        // Toggle tipe input
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle ikon (Bootstrap Icons)
        const icon = this.querySelector('i');
        if (icon) {
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    });
}