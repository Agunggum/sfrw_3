<?php
use app\Models\Users;

class UserController extends Controller {

    /**
     * Menampilkan daftar semua pengguna.
     * Membutuhkan peran 'admin'.
     */
    public function daftar() {
        $users = PembangunKueri::tabel(Users::schematable())->urutkan('id', 'DESC')->dapatkan();
        // Untuk tujuan demonstrasi, kita akan membuat file view sederhana jika belum ada
        if (!file_exists(MODPATH.'view/users/daftar.view'.EXT)) {
            if (!is_dir(MODPATH.'view/users')) mkdir(MODPATH.'view/users');
            file_put_contents(MODPATH.'view/users/daftar.view'.EXT, '<h1>Daftar Pengguna</h1><pre><?php print_r($data["users"]); ?></pre>');
        }
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
        $input = kiriman();
        
        // Filter hanya kolom yang diizinkan (fillable)
        $fillable = explode(", ", Users::schemafillable());
        $data = array_intersect_key($input, array_flip($fillable));

        if (empty($data['username']) || empty($data['password'])) {
            alert('warning', 'Gagal', 'Username dan Password wajib diisi.');
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Enkripsi password modern
        
        try {
            PembangunKueri::tabel(Users::schematable())->sisipkan($data);
            alert('success', 'Berhasil', 'Pengguna baru berhasil disimpan.', BASEURL . 'users');
        } catch (\Exception $e) {
            alert('warning', 'Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu pengguna.
     * Membutuhkan login.
     */
    public function lihat($id) {
        $user = PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->pertama();
        if (!$user) {
            alert('warning', 'Tidak ditemukan', 'Pengguna tidak ditemukan.', BASEURL . 'users');
            return;
        }
        tampilan('users/lihat', ['user' => $user]);
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Membutuhkan peran 'admin'.
     */
    public function formEdit($id) {
        $user = PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->pertama();
        if (!$user) {
            alert('warning', 'Tidak ditemukan', 'Pengguna tidak ditemukan.', BASEURL . 'users');
            return;
        }
        tampilan('users/edit', ['user' => $user]);
    }

    /**
     * Memperbarui data pengguna di database.
     * Membutuhkan peran 'admin'.
     */
    public function perbarui($id) {
        $input = kiriman();
        
        // Filter hanya kolom yang diizinkan (fillable)
        $fillable = explode(", ", Users::schemafillable());
        $data = array_intersect_key($input, array_flip($fillable));

        // Jangan perbarui password jika kosong
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); 
        }

        try {
            PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->perbarui($data);
            alert('success', 'Berhasil', "Pengguna dengan ID {$id} berhasil diperbarui.", BASEURL . 'users');
        } catch (\Exception $e) {
            alert('warning', 'Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pengguna dari database.
     * Membutuhkan peran 'admin'.
     */
    public function hapus($id) {
        try {
            PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->hapus();
            alert('success', 'Berhasil', "Pengguna dengan ID {$id} berhasil dihapus.", BASEURL . 'users');
        } catch (\Exception $e) {
            alert('warning', 'Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
