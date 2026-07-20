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
    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
        $data['title'] = "S-FRW Masuk";
    }else{
        $data['title'] = "S-FRW Login";
    }
    require_once tampilan('login', $data);
});

Rute::ambil('register', function() {
    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
        $data['title'] = "S-FRW Daftar";
    }else{
        $data['title'] = "S-FRW Register";
    }
    require_once tampilan('register', $data);
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
    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
        $data['title'] = "S-FRW Lupa Password";
    }else{
        $data['title'] = "S-FRW Forgot Password";
    }
    require_once tampilan('forgot-password', $data);
});

Rute::ambil('forgot-password/{s}', function($s) {
    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
        $data['title'] = "S-FRW Lupa Password";
    }else{
        $data['title'] = "S-FRW Forgot Password";
    }
    $data['s'] = PembangunKueri::tabel('forgot_link')
                ->pilih('end_time')
                ->dimana('target_link', '=', $s)
                ->pertama();
    require_once tampilan('forgot-password', $data);
});

// Halaman Tabel (Datatable)
Rute::ambil('datatable', function() {
    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
        $data['title'] = "S-FRW Tabel";
    }else{
        $data['title'] = "S-FRW Datatable";
    }
    require_once tampilan('table', $data);
});

// Halaman Keluar (Signout)
Rute::ambil('signout', 'Logincontroller@signout');

// Halaman Logs (dengan parameter file)
Rute::ambil('logs', function() {
    arahkan(BASEURL."logs/error");
});
Rute::ambil('logs/{file}', function($file) {
    require_once tampilan('logs', [
        $data['title'] = "S-FRW Logs",
        $data['breadcrumb'] = "S-FRW Logs",
        $data['icon'] = "bi bi-logs",
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
        if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
            $data['title'] = "S-FRW Dasbor";
        }else{
            $data['title'] = "S-FRW Dashboard";
        }
        $data['id'] = 0;
        require_once tampilan('dashboard/dashboard', $data);
    });

    Rute::middleware('Role:admin')->grup(function() {
        Rute::ambil('users', 'UserController@daftar');
        Rute::ambil('userslist', 'UserController@daftarlist');
        Rute::ambil('users/create', 'UserController@formTambah');
        Rute::kirim('users/simpan', 'UserController@simpan');
        Rute::ambil('users/{id}/edit', 'UserController@formEdit');
        Rute::kirim('users/{id}/perbarui', 'UserController@perbarui');
        Rute::ambil('users-hapus/{id}', 'UserController@hapus');
    });

    // Rute ini hanya memerlukan login, tanpa peran spesifik
    Rute::ambil('users/{id}/see', 'UserController@lihat');
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
