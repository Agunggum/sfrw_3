# S-FRW Framework (v3.0) 🚀

**Sunda Framework (S-FRW)** adalah framework PHP modern yang dirancang untuk kecepatan, kesederhanaan, dan pengalaman pengembangan yang luar biasa (**Vibe Coding**). Framework ini menggabungkan kekuatan PHP di sisi server dengan reaktivitas modern di sisi klien.

## ✨ Fitur Unggulan

-   **🚀 SPA (Single Page Application)**: Navigasi antar halaman instan tanpa refresh penuh menggunakan sistem *AJAX-driven navigation*.
-   **⚛️ Reactive UI dengan lit-html**: Integrasi native dengan `lit-html` untuk membangun komponen UI yang reaktif dan ringan.
-   **🔥 Native Live Reload**: Pembaruan tampilan secara instan saat Anda mengubah kode, menggunakan teknologi *Server-Sent Events (SSE)*.
-   **🛡️ PHP 8.1 Ready**: Sepenuhnya dioptimalkan untuk PHP 8.1+ dengan driver database `mysqli` yang stabil dan aman.
-   **🛠️ Advanced Error Handler**: Tampilan error yang cantik dengan cuplikan kode dan sorotan baris untuk mempercepat debugging.
-   **🌗 Dark Mode Native**: Dukungan mode gelap dan terang yang tersinkronisasi di seluruh aplikasi.
-   **💾 Singleton Database**: Koneksi database yang efisien dan terpusat melalui Container.

## 📁 Struktur Folder Utama

-   `app/`: Logika inti aplikasi dan model berbasis skema.
-   `mvc/controller/`: Pengendali alur aplikasi.
-   `mvc/view/`: Tempat semua tampilan dengan ekstensi `.view.php`.
-   `library/`: Mesin utama framework (Core, PembangunKueri, Container).
-   `web/route.php`: Pusat pengaturan URL/Rute aplikasi.
-   `public/`: Folder publik untuk aset dan server live reload.

## 🛠️ Panduan Pengembangan (Vibe Coding)

S-FRW mendukung gaya pengembangan **"Vibe Coding"**, di mana Anda fokus pada membangun fitur dengan cepat dan intuitif.

1.  **Lihat Filosofi Desain**: Pelajari prinsip dasar di [DESIGN.MD](file:///d:/virtual-server/xampp%20php8.1/htdocs/sfrw_3/DESIGN.MD).
2.  **Mulai Membangun**: Ikuti contoh praktis membangun fitur di [APP.MD](file:///d:/virtual-server/xampp%20php8.1/htdocs/sfrw_3/APP.MD).

## 🔧 Konfigurasi Cepat

### Database (`library/config.txt`)
Format: `host, user, pass, db_name, driver`
```text
localhost, root, , my_database, MySqli
```

### Environment (`env.php`)
```php
define('ENVIRONMENT', 'local'); // Gunakan 'local' untuk mengaktifkan Live Reload
define('DEBUG', 'true');        // Tampilkan detail error saat pengembangan
define('BASEURL', 'http://localhost/sfrw_3/');
```

## 🛡️ Keamanan & Performa

-   **SQL Injection Protection**: PembangunKueri secara otomatis melakukan *escaping* pada semua data input.
-   **XSS Protection**: Helper `anti_injection()` tersedia untuk membersihkan input pengguna.
-   **Session Fixation Protection**: Regenerasi ID session otomatis saat login sukses.
-   **SSE Stream**: Live reload menggunakan koneksi persisten yang sangat hemat resource dibandingkan polling tradisional.

## 🤝 Kontribusi

S-FRW dibangun dengan ❤️ untuk komunitas developer yang menginginkan framework ringan namun bertenaga. Silakan kirimkan saran atau laporkan bug untuk membantu kami berkembang.

---
**Sunda Framework (S-FRW)** - *Fast, Simple, and Reactive.*
