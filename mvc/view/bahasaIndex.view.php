<script>
document.addEventListener('DOMContentLoaded', () => {
        let dictionary = {}; 
        // MEMBACA LOCALSTORAGE: Jika sebelumnya sudah memilih bahasa, pakai itu. Jika belum, default ke 'id'
        let currentLang = localStorage.getItem('user_language') || 'id';

        // Amankan fungsi render awal ini agar langsung dieksekusi tanpa nunggu script lain
        function renderLanguageOnInit() {
            // Ambil bahasa aktif dari atribut HTML yang kita set di <head> tadi
            const lang = document.documentElement.getAttribute('data-lang-current') || 'id';

            // 1. Ubah ikon bendera utama di awal
            const flagMapping = { 'en': 'fi-us', 'id': 'fi-id' };
            const currentFlagEl = document.getElementById('current-flag-icon');
            if (currentFlagEl && flagMapping[lang]) {
                currentFlagEl.classList.remove('fi-id', 'fi-us');
                currentFlagEl.classList.add(flagMapping[lang]);
            }

            // 2. Render teks dari objek kamus (dictionary)
            document.querySelectorAll('[data-lang-id]').forEach(el => {
                const id = el.getAttribute('data-lang-id');
                if (typeof dictionary !== 'undefined' && dictionary[lang] && dictionary[lang][id]) {
                    el.textContent = dictionary[lang][id];
                }
            });

            // 3. KUNCI UTAMA: Tampilkan halaman setelah translasi selesai agar tidak flash!
            document.body.classList.add('lang-ready');
        }

        // Jalankan langsung begitu DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderLanguageOnInit);
        } else {
            renderLanguageOnInit();
        }

        // 1. Load Kamus saat halaman dibuka
        async function loadDictionary() {
            try {
                const response = await fetch('<?php echo BASEURL; ?>getLang');
                if (response.ok) {
                    dictionary = await response.json();
                    // Terapkan bahasa yang tersimpan di localStorage
                    changeLanguage(currentLang);
                }
            } catch (error) {
                console.log("Kamus baru atau pertama kali akan dibuat saat ada perubahan struktur.");
            }
        }

        // 2. Fungsi Ganti Bahasa di Layar
        window.changeLanguage = function(lang) {
            currentLang = lang;
            // SIMPAN KE LOCALSTORAGE
            localStorage.setItem('user_language', lang);

            // KUNCI PERBAIKAN BENDERA: Petakan kode bahasa ke kode class bendera (Flag Icons)
            const flagMapping = {
                'en': 'fi-us',
                'id': 'fi-id'
            };

            // Ambil elemen bendera utama di button toggle
            const currentFlagEl = document.getElementById('current-flag-icon');
            if (currentFlagEl && flagMapping[lang]) {
                // Hapus class bendera lama yang mungkin menempel
                currentFlagEl.classList.remove('fi-id', 'fi-us');
                // Tambahkan class bendera yang baru dipilih
                currentFlagEl.classList.add(flagMapping[lang]);
            }

            // Eksekusi perubahan teks berdasarkan dictionary bawaan Anda
            document.querySelectorAll('[data-lang-id]').forEach(el => {
                const id = el.getAttribute('data-lang-id');
                if (typeof dictionary !== 'undefined' && dictionary[lang] && dictionary[lang][id]) {
                    el.textContent = dictionary[lang][id];
                }
            });
        }

        // OPMIONAL: Jalankan fungsi saat halaman dimuat pertama kali 
        // Agar bendera langsung menyesuaikan bahasa terakhir yang disimpan di localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('user_language') || 'id'; // Default 'id' jika kosong
            changeLanguage(savedLang);
        });

        // 3. Fungsi Translate Otomatis via Google Translate API
        async function translateText(text, fromLang, toLang) {
            try {
                const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${fromLang}&tl=${toLang}&dt=t&q=${encodeURIComponent(text)}`;
                const response = await fetch(url);
                const result = await response.json();
                return result[0][0][0];
            } catch (error) {
                console.error("Gagal Translate:", error);
                return text;
            }
        }

        // 4. Proses Sync Struktur & Otomatis Menerjemahkan
        async function syncAndTranslate() {
            let elementsData = [];
            let newIdKamus = {};
            let newEnKamus = {};

            const elements = document.querySelectorAll('[data-lang-id]');

            for (let el of elements) {
                const langId = el.getAttribute('data-lang-id');
                let textIndo = el.textContent;
                
                if (currentLang === 'en' && dictionary['id'] && dictionary['id'][langId]) {
                    textIndo = dictionary['id'][langId];
                }

                let textEnglish = '';

                // Cek apakah di kamus lokal sudah ada (baik bawaan maupun hasil edit manual)
                if (dictionary['en'] && dictionary['en'][langId] && dictionary['en'][langId].trim() !== '') {
                    textEnglish = dictionary['en'][langId];
                } else {
                    // Jika benar-benar baru dan belum ada di kamus, baru ditranslate otomatis
                    textEnglish = await translateText(textIndo, 'id', 'en');
                }

                newIdKamus[langId] = textIndo;
                newEnKamus[langId] = textEnglish;

                elementsData.push({
                    lang_id: langId,
                    element_id: el.id || null,
                    element_class: el.className || null
                });
            }

            // Buat objek kamus sementara untuk dikirim ke backend
            let tempDictionary = { id: newIdKamus, en: newEnKamus };

            // Kirim data baru ke backend
            fetch('<?php echo BASEURL; ?>lang-update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    struktur: elementsData,
                    kamus: tempDictionary // Kirim data berjalan
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Backend Sync:", data.message);
                
                // Gabungkan juga di sisi frontend agar jika ada key baru langsung masuk, 
                // namun key lama hasil edit manual Anda tetap aman tidak ter-reset sebelum refresh halaman.
                dictionary.id = { ...tempDictionary.id, ...dictionary.id };
                dictionary.en = { ...tempDictionary.en, ...dictionary.en };
                
                changeLanguage(currentLang);
            })
            .catch(err => console.error("Sync Error:", err));
        }

        // 5. Observer untuk mendeteksi perubahan Class atau ID
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && (mutation.attributeName === 'class' || mutation.attributeName === 'id')) {
                    syncAndTranslate(); 
                }
            });
        });

        document.querySelectorAll('[data-lang-id]').forEach(el => {
            observer.observe(el, { attributes: true });
        });

        // Jalankan fungsi load data pertama kali saat web dibuka
        window.addEventListener('DOMContentLoaded', loadDictionary);

        // Fungsi testing simulasi
        window.simulasiPerubahan = function() {
            const h1 = document.getElementById('id-pemicu');
            if (h1) {
                h1.classList.toggle('highlight');
                /*h1.id = h1.id === 'main-title' ? 'new-main-title' : 'main-title';*/
            }
        }
});
</script>