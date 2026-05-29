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