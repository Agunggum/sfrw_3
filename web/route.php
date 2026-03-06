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

// Halaman Lupa Password
Rute::ambil('forgot-password', function() {
    require_once tampilan('forgot-password');
});

// Halaman Tabel (Datatable)
Rute::ambil('datatable', function() {
    require_once tampilan('table');
});

// Halaman Keluar (Signout)
Rute::ambil('signout', function() {
    require_once tampilan('signout');
});

// Halaman Logs (dengan parameter file)
Rute::ambil('logs-', function() {
    require_once vendors('logcarbon/logcarbon');
    require_once tampilan('logs', [
        $data['title'] = "Logs",
        $data['breadcrumb'] = "Logs",
        $data['icon'] = "fa fa-logs",
    ]);
});

// Halaman Daftar File Logs
Rute::ambil('logsfiles', function() {
    require_once tampilan('logsfiles');
});

// Rute yang memerlukan autentikasi dan peran admin untuk CRUD pengguna
Rute::middleware('Auth')->grup(function() {
    Rute::middleware('Role:admin')->grup(function() {
        Rute::ambil('users', 'UserController@daftar');
        Rute::ambil('users/tambah', 'UserController@formTambah');
        Rute::kirim('users/simpan', 'UserController@simpan');
        Rute::ambil('users/{id}/edit', 'UserController@formEdit');
        Rute::kirim('users/{id}/perbarui', 'UserController@perbarui');
        Rute::ambil('users/{id}/hapus', 'UserController@hapus');
    });

    // Rute ini hanya memerlukan login, tanpa peran spesifik
    Rute::ambil('users/{id}', 'UserController@lihat');
});

// Jalankan Rute
Rute::jalankan(ROUTE, $_SERVER['REQUEST_METHOD']);

/* End of file route.php */
/* Location: ./web/route.php */
