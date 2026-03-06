<?php
namespace app\Http\Middleware;

class Role {
    /**
     * Tangani permintaan yang masuk berdasarkan peran.
     *
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function tangani($next, $role) {
        $peranPengguna = sesi('accessme');

        if ($peranPengguna !== $role) {
            // Jika peran tidak sesuai, tampilkan halaman error atau alihkan
            header("HTTP/1.0 403 Forbidden");
            echo "Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.";
            exit();
        }

        // Jika peran sesuai, lanjutkan ke permintaan berikutnya
        return $next();
    }
}
