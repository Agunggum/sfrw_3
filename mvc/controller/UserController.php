<?php
use app\Models\Users;

class UserController extends Controller {

    /**
     * Menampilkan daftar semua pengguna.
     * Membutuhkan peran 'admin'.
     */
    public function daftar() {
        $users = Users::semua(); // Asumsi ada method `semua()` di model Users
        tampilan('users/daftar', ['users' => $users]);
    }

    /**
     * Menampilkan form untuk menambah pengguna baru.
     * Membutuhkan peran 'admin'.
     */
    public function formTambah() {
        tampilan('users/tambah');
    }

    /**
     * Menyimpan pengguna baru ke database.
     * Membutuhkan peran 'admin'.
     */
    public function simpan() {
        // Logika untuk validasi dan menyimpan data dari kiriman()
        $data = kiriman();
        // ... (logika penyimpanan)
        echo "Pengguna baru berhasil disimpan.";
    }

    /**
     * Menampilkan detail satu pengguna.
     * Membutuhkan login.
     */
    public function lihat($id) {
        $user = Users::cari($id); // Asumsi ada method `cari()`
        tampilan('users/lihat', ['user' => $user]);
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Membutuhkan peran 'admin'.
     */
    public function formEdit($id) {
        $user = Users::cari($id);
        tampilan('users/edit', ['user' => $user]);
    }

    /**
     * Memperbarui data pengguna di database.
     * Membutuhkan peran 'admin'.
     */
    public function perbarui($id) {
        // Logika untuk validasi dan pembaruan data
        echo "Pengguna dengan ID {$id} berhasil diperbarui.";
    }

    /**
     * Menghapus pengguna dari database.
     * Membutuhkan peran 'admin'.
     */
    public function hapus($id) {
        // Logika untuk menghapus pengguna
        echo "Pengguna dengan ID {$id} berhasil dihapus.";
    }
}
