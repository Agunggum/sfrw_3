document.addEventListener('DOMContentLoaded', () => {
            // --- 1. Sidebar Toggler Logic ---
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            const toggleSidebar = (e) => {
                e.preventDefault();
                document.body.classList.toggle('sb-toggled');
            };

            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);


            // --- 2. Advanced Multi-Theme Logic (Light, Dark, System) ---
            const htmlEl = document.documentElement;
            
            // Mendapatkan tema tersimpan atau default ke 'auto' (System)
            const getStoredTheme = () => localStorage.getItem('theme') || 'auto';
            const setStoredTheme = theme => localStorage.setItem('theme', theme);

            // Menentukan tema render aktual
            const setTheme = theme => {
                if (theme === 'auto') {
                    htmlEl.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                } else {
                    htmlEl.setAttribute('data-bs-theme', theme);
                }
                showActiveTheme(theme);
            };

            // Menyelaraskan tampilan Dropdown UI (Icon & Checkmark)
            const showActiveTheme = (theme) => {
                const themeIconActive = document.querySelector('#themeIconActive');
                const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                
                if (!btnToActive) return;

                // Reset status centang aktif
                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');
                    const checkIcon = element.querySelector('.bi-check2');
                    if (checkIcon) checkIcon.classList.add('d-none');
                });

                // Set status aktif pada item yang dipilih
                btnToActive.classList.add('active');
                btnToActive.setAttribute('aria-pressed', 'true');
                const activeCheckIcon = btnToActive.querySelector('.bi-check2');
                if (activeCheckIcon) activeCheckIcon.classList.remove('d-none');

                // Update icon utama pada tombol navbar dropdown
                const iconEl = btnToActive.querySelector('i');
                if (iconEl && themeIconActive) {
                    themeIconActive.className = iconEl.className.replace('me-2', '').replace('opacity-50', '').trim();
                    
                    if(theme === 'dark') {
                        themeIconActive.classList.add('text-warning');
                    } else if (theme === 'light') {
                        themeIconActive.classList.remove('text-warning');
                    }
                }
            };

            // Inisialisasi tema saat pertama kali halaman dimuat
            setTheme(getStoredTheme());

            // Event listener klik pada setiap opsi pilihan tema
            document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value');
                    setStoredTheme(theme);
                    setTheme(theme);
                });
            });

            // Mendengarkan perubahan skema warna sistem OS secara real-time
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme === 'auto') {
                    setTheme('auto');
                }
            });

            // --- 3. Dynamic Name Avatar Logic ---
            const updateAvatarByName = () => {
                const avatarEl = document.getElementById('userAvatar');
                if (!avatarEl) return;

                // 1. Ambil nama dari atribut data-name
                const fullName = avatarEl.getAttribute('data-name') || 'User';
                
                // 2. Logika pencarian inisial (Contoh: "Mohammad Agung" -> "MA")
                const words = fullName.trim().split(' ');
                let initials = '';
                if (words.length > 0 && words[0] !== '') {
                    initials += words[0][0]; // Huruf pertama kata pertama
                    if (words.length > 1) {
                        initials += words[words.length - 1][0]; // Huruf pertama kata terakhir
                    }
                } else {
                    initials = 'U';
                }
                
                // Taruh inisial ke atribut HTML agar dibaca oleh CSS ::after
                avatarEl.setAttribute('data-initials', initials);

                // 3. Logika generates warna background unik berdasarkan string Nama (Hashing)
                let hash = 0;
                for (let i = 0; i < fullName.length; i++) {
                    hash = fullName.charCodeAt(i) + ((hash << 5) - hash);
                }
                
                // Konversi hash menjadi kode warna HSL yang aman di mata (tidak terlalu pucat/gelap)
                const h = Math.abs(hash % 360);
                const s = 65; // Saturation 65%
                const l = 45; // Lightness 45% untuk kontras teks putih yang baik
                
                avatarEl.style.backgroundColor = `hsl(${h}, ${s}%, ${l}%)`;
            };

            // Jalankan fungsi avatar
            updateAvatarByName();
});