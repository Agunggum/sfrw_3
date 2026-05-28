<?php
use app\Models\Users;

class UserController extends Controller {

    /**
     * Menampilkan daftar semua pengguna.
     * Membutuhkan peran 'admin'.
     */
    public static function daftar() {
        if (!file_exists(MODPATH.'view/users/daftar.view'.EXT)) {
            if (!is_dir(MODPATH.'view/users')) mkdir(MODPATH.'view/users');
            file_put_contents(MODPATH.'view/users/daftar.view'.EXT, '<h1>Daftar Pengguna</h1><pre><?php print_r($data["users"]); ?></pre>');
        }
        require_once tampilan('users/daftar');
    }

    public static function daftarlist($key) {
        if ($key != encrypt(date('YmdHi'))) {
            echo json_encode(['status' => '404', 'message' => 'Kunci tidak valid'], JSON_PRETTY_PRINT);
            return;
        }
        $users = PembangunKueri::tabel(Users::schematable())->pilih('*')->urutkan('id', 'ASC')->dapatkan();

        $output_data = [];
        foreach ($users as $row) {
            $output_data[] = [
                "id" => $row['id'],
                "id_encrypted" => encrypt($row['id']), // Tambahkan kolom terenkripsi di sini
                "fullname" => $row['fullname'],
                "username" => $row['username'],
                "email" => $row['email'],
                "role" => $row['role']
            ];
        }
        
        echo json_encode($output_data, JSON_PRETTY_PRINT);
    }

    /**
     * Menampilkan form untuk menambah pengguna baru.
     * Membutuhkan peran 'admin'.
     */
    public static function formTambah() {
        require_once tampilan('users/tambah');
    }

    /**
     * Menyimpan pengguna baru ke database.
     * Membutuhkan peran 'admin'.
     */
    public static function simpan() {
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
    public static function lihat($id) {
        $user = PembangunKueri::tabel(Users::schematable())->dimana('id', decrypt($id))->pertama();
        if (!$user) {
            alert('warning', 'Tidak ditemukan', 'Pengguna tidak ditemukan.', BASEURL . 'users');
            return;
        }
        require_once tampilan('users/lihat', ['user' => $user]);
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Membutuhkan peran 'admin'.
     */
    public static function formEdit($id) {
        $user = PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->pertama();
        if (!$user) {
            alert('warning', 'Tidak ditemukan', 'Pengguna tidak ditemukan.', BASEURL . 'users');
            return;
        }
        require_once tampilan('users/edit', ['user' => $user]);
    }

    /**
     * Memperbarui data pengguna di database.
     * Membutuhkan peran 'admin'.
     */
    public static function perbarui($id) {
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
    public static function hapus($id) {
        PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->hapus();
        $output_data[] = [
            "status" => "success",
            "message" => "Pengguna dengan ID {$id} berhasil dihapus.",
        ];
        echo json_encode($output_data, JSON_PRETTY_PRINT);
    }
}
