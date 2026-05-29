<?php
if ( ! 'web') exit('No direct script access allowed');

use muhammad\routing\Rute;

/*
|--------------------------------------------------------------------------
| Pengaturan Rute (Laravel Style)
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendefinisikan rute untuk aplikasi Anda.
| Rute-rute ini akan diproses oleh muhammad\routing\Rute.
|
*/

// Halaman Beranda
Rute::ambil('/', function() {
    return Indexcontroller::index();
});

// Halaman Login
Rute::ambil('login', function() {
    require_once tampilan('login');
});

Rute::ambil('register', function() {
    require_once tampilan('register');
});

Rute::kirim('authlogin', function() {
    Logincontroller::loginform(BASEURL);
});

Rute::kirim('authforgotpassword', function() {
    Logincontroller::forgotform(BASEURL);
});

Rute::kirim('authforgot/{s}', function($s) {
    Logincontroller::forgotnewform(BASEURL, $s);
});

// Halaman Lupa Password
Rute::ambil('forgot-password', function() {
    require_once tampilan('forgot-password');
});

Rute::ambil('forgot-password/{s}', function($s) {
    require_once tampilan('forgot-password', [
        $data['s'] = PembangunKueri::tabel('forgot_link')
                ->pilih('end_time')
                ->dimana('target_link', '=', $s)
                ->pertama()
    ]);
});

// Halaman Tabel (Datatable)
Rute::ambil('datatable', function() {
    require_once tampilan('table');
});

// Halaman Keluar (Signout)
Rute::ambil('signout', 'Logincontroller@signout');

// Halaman Logs (dengan parameter file)
Rute::ambil('logs/{file}', function($file) {
    require_once vendors('logcarbon/logcarbon');
    require_once tampilan('logs', [
        $data['title'] = "Logs",
        $data['breadcrumb'] = "Logs",
        $data['icon'] = "fa fa-logs",
        $data['file'] = $file
    ]);
});

// Halaman Daftar File Logs
Rute::ambil('logsfiles/{file}', function($file) {
    require_once tampilan('logsfiles');
});

// Rute yang memerlukan autentikasi dan peran admin untuk CRUD pengguna
Rute::middleware('Auth')->grup(function() {
    Rute::ambil('dashboard', function() {
        require_once tampilan('dashboard/dashboard');
    });

    Rute::middleware('Role:admin')->grup(function() {
        Rute::ambil('users', 'UserController@daftar');
        Rute::ambil('userslist/{key}', 'UserController@daftarlist');
        Rute::ambil('users/tambah', 'UserController@formTambah');
        Rute::kirim('users/simpan', 'UserController@simpan');
        Rute::ambil('users/{id}/edit', 'UserController@formEdit');
        Rute::kirim('users/{id}/perbarui', 'UserController@perbarui');
        Rute::ambil('users-hapus/{id}', 'UserController@hapus');
    });

    // Rute ini hanya memerlukan login, tanpa peran spesifik
    Rute::ambil('users/{id}', 'UserController@lihat');
});

// Rute khusus untuk setup database (JANGAN GUNAKAN DI LINGKUNGAN PRODUKSI)
Rute::ambil('setup-database/{kunci}', 'DatabaseSeederController@jalankan');

Rute::ambil('getLang', 'IndexController@Lang');
Rute::kirim('lang-update', 'IndexController@updateLang');
Rute::ambil('getLangLogin', 'IndexController@LangLogin');
Rute::kirim('lang-update-Login', 'IndexController@updateLangLogin');

// Jalankan Rute
Rute::jalankan(ROUTE, $_SERVER['REQUEST_METHOD']);

/* End of file route.php */
/* Location: ./web/route.php */
