# Design Principles & Vibe Coding 🎨

Dokumen ini menjelaskan filosofi desain di balik **S-FRW Framework** dan bagaimana Anda dapat memaksimalkan produktivitas menggunakan pendekatan **"Vibe Coding"**.

## 🧠 Filosofi Desain

S-FRW dirancang untuk menjadi framework yang "invisible". Fokus utamanya adalah:
1.  **Kecepatan**: Baik dalam performa eksekusi maupun waktu pengembangan.
2.  **Sederhana**: Mengurangi abstraksi yang tidak perlu agar developer tetap dekat dengan kode native (PHP/JS).
3.  **Modern**: Mengintegrasikan teknologi terbaru seperti SPA, lit-html, dan SSE secara native.

## ⚡ Vibe Coding: Panduan Developer

"Vibe Coding" adalah cara membangun aplikasi dengan aliran (flow) yang cepat, sering kali dibantu oleh AI, di mana Anda fokus pada *intent* (niat) daripada *boilerplate*.

### 1. Reaktivitas Tanpa Beban
Alih-alih menggunakan framework JS yang berat, S-FRW menggunakan **lit-html**.
- **Prinsip**: Gunakan PHP untuk data server, gunakan lit-html untuk tampilan interaktif.
- **Vibe**: Tulis HTML Anda di dalam template literal JavaScript, biarkan S-FRW menangani sinkronisasi datanya.

### 2. Alur SPA Otomatis
Navigasi antar halaman terjadi secara instan tanpa refresh penuh.
- **Vibe**: Buat link `<a>` seperti biasa, framework akan secara otomatis mengubahnya menjadi AJAX fetch. Anda tidak perlu mengatur router di sisi klien secara manual.

### 3. Native Live Reload
Pengalaman pengembangan yang sangat responsif.
- **Vibe**: Simpan file (`Ctrl+S`), dan lihat perubahan Anda muncul secara instan di browser melalui koneksi SSE yang persisten.

---

## 🛠️ Pola Arsitektur

### Unified Database Connection
S-FRW menggunakan pola *Singleton-like* untuk koneksi database.
```php
// Diakses secara global melalui Container
$mysqli = get_db_conn();
```
Ini memastikan hanya ada satu koneksi aktif per request, sangat hemat resource.

### Reactive PHP-to-JS Integration
Meneruskan data dari logika server ke komponen UI dilakukan secara transparan.
```php
// Di View (.view.php)
<?php echo pass_to_js($user_data); ?>

<script type="module">
    const { html, render } = window.lit;
    // Data otomatis tersedia di window.pageData
    render(html`<h1>Halo, ${window.pageData.name}</h1>`, container);
</script>
```

### Hybrid Error Handling
Sistem error handler S-FRW bertindak sebagai asisten pribadi saat pengembangan.
- **Mode DEBUG**: Memberikan cuplikan kode dan sorotan baris yang salah secara instan.
- **Mode PROD**: Menjaga kerahasiaan sistem dengan tampilan profesional 500/404.

---

## 🚀 Tips untuk "Vibe Coding"
- **Fokus pada View**: Mulailah dari `mvc/view/`, buat tampilan yang Anda inginkan, lalu biarkan framework membantu Anda menghubungkannya ke Controller.
- **Gunakan Helper**: Manfaatkan `anti_injection()`, `date_indo()`, dan `pass_to_js()` untuk mempercepat penulisan logika rutin.
- **AI-Friendly**: Struktur folder S-FRW yang sangat eksplisit memudahkan AI untuk memahami konteks proyek Anda dan memberikan saran kode yang akurat.
