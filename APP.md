# App Development Guide 🚀

Panduan praktis untuk membangun aplikasi menggunakan **S-FRW Framework**.

## 🏗️ Struktur Aplikasi (The Vibe Way)

Setiap fitur biasanya melibatkan tiga komponen utama:
1.  **Route**: Definisikan URL di `web/route.php`.
2.  **Controller**: Logika bisnis di `mvc/controller/`.
3.  **View**: Tampilan reaktif di `mvc/view/` (ekstensi `.view.php`).

---

## 💡 Contoh Implementasi: Fitur "User Profile"

### 1. Definisikan Rute
```php
// web/route.php
Rute::ambil('profile', 'UserController@show');
```

### 2. Buat Controller
```php
// mvc/controller/UserController.php
class UserController extends Controller {
    public function show() {
        $user = PembangunKueri::tabel('users')
                ->dimana('id', '=', $_SESSION['user_id'])
                ->pertama();

        require_once view('profile', [
            'title' => 'Profil Saya',
            'user' => $user
        ]);
    }
}
```

### 3. Buat View Reaktif (Lit-HTML)
```php
// mvc/view/profile.view.php
<?php require_once view('header'); ?>

<!-- Teruskan data PHP ke JavaScript -->
<?php echo pass_to_js($data['user']); ?>

<div class="container mt-5">
    <div id="profile-app"></div>
</div>

<script type="module">
    const { html, render } = window.lit;
    
    // Template Lit-HTML
    const ProfileTemplate = (user) => html`
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name=${user.fullname}" class="rounded-circle mb-3">
                <h3>${user.fullname}</h3>
                <p class="text-muted">${user.email}</p>
                <button @click=${() => alert('Vibe Coding!')} class="btn btn-primary">
                    Edit Profil
                </button>
            </div>
        </div>
    `;

    // Render secara reaktif
    render(ProfileTemplate(window.pageData), document.getElementById('profile-app'));
</script>

<?php require_once view('footer'); ?>
```

---

## ⚡ Fitur Utama untuk Developer

### Live Reload (Native SSE)
Anda tidak perlu merefresh browser. Begitu Anda mengubah file di `mvc/view/`, browser akan memperbarui tampilan secara otomatis tanpa kehilangan state aplikasi.

### SPA Navigation
Pindah halaman terasa instan:
```html
<!-- Link ini akan dimuat secara SPA otomatis -->
<a href="<?php echo BASEURL; ?>profile" class="nav-link">Profil</a>
```

### Advanced Query Builder
Interaksi database yang sangat mudah:
```php
// Mengambil satu data
$data = PembangunKueri::tabel('users')->dimana('active', '=', 'Y')->pertama();

// Mengambil banyak data
$users = PembangunKueri::tabel('users')->dapatkan();

// Update data
PembangunKueri::tabel('users')->dimana('id', '=', 1)->perbarui(['active' => 'N']);
```

---

## 🛠️ Debugging "Vibe"
Jika terjadi error, jangan panik! S-FRW akan menampilkan:
1.  **Pesan Error** yang jelas.
2.  **File & Baris** yang bermasalah.
3.  **Cuplikan Kode** langsung di browser Anda dengan sorotan warna pada baris yang salah.
4.  Tombol **"Coba Lagi"** untuk memvalidasi perbaikan Anda secara instan.
