<?php
namespace app\Http\Middleware;

class Auth {
    /**
     * Tangani permintaan yang masuk.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function tangani($next) {
        if (!sesi('username')) {
            // Simpan URL tujuan saat ini agar bisa kembali setelah login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];

            // Jika pengguna tidak login, alihkan ke halaman login
            alihkan(BASEURL . 'login');
            exit();
        }

        // Jika sudah login, lanjutkan ke permintaan berikutnya
        return $next();
    }
}
