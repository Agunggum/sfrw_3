<?php
require_once vendors('logcarbon/logcarbon');
require_once services('Validator');

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
        require_once tampilan('users/daftar', [
            $data['title'] = "S-FRW Data Pengguna",
        ]);
    }

    public static function daftarlist() {
        // Hilangkan verifikasi kunci yang kadaluwarsa agar stabil di SPA
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
        
        echo ambilJson($output_data);
    }

    /**
     * Menampilkan form untuk menambah pengguna baru.
     * Membutuhkan peran 'admin'.
     */
    public static function formTambah() {
        require_once tampilan('users/create', [
            $data['title'] = "S-FRW Form Tambah Pengguna",
            $data['id'] = 0,
        ]);
    }

    /**
     * Menyimpan pengguna baru ke database.
     * Membutuhkan peran 'admin'.
     */
    public static function simpan() {
        // Set response header agar dibaca sebagai JSON oleh AJAX
        header('Content-Type: application/json');

        $input = kiriman();

        // 1. Jalankan Validasi Input
        $rules = [
            'username' => 'required|minlength:3|regex:/^[a-z0-9_]+$/',
            'password' => 'required|minlength:8',
            'fullname' => 'required',
            'email'    => 'required|email|unique:users,email'
        ];

        if (!Validator::validate($input, $rules)) {
            // Kembalikan error validasi dalam bentuk JSON
            echo json_encode([
                'status'  => 'error',
                'message' => Validator::getErrorsString()
            ]);
            exit;
        }
        
        // 2. Filter hanya kolom yang diizinkan (fillable) setelah lolos validasi
        $fillable = explode(", ", Users::schemafillable());
        $data = array_intersect_key($input, array_flip($fillable));

        // 3. Enkripsi password modern
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        try {
            PembangunKueri::tabel(Users::schematable())->sisipkan($data);
            
            // Respon sukses untuk AJAX
            echo json_encode([
                'status'  => 'success',
                'message' => 'Pengguna baru berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            // Respon gagal jika ada error database / unique constraint
            echo json_encode([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
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
        require_once tampilan('users/profile', [
            $data['title'] = "S-FRW Lihat Pengguna",
            $data['id'] = $id,
            $data['user'] = $user
        ]);
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
        require_once tampilan('users/edit', [
            $data['title'] = "S-FRW Form Edit Pengguna",
            $data['user'] = $user
        ]);
    }

    /**
     * Memperbarui data pengguna di database.
     * Membutuhkan peran 'admin'.
     */
    public static function perbarui($id) {
        header('Content-Type: application/json');

        $input = kiriman();
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
            exit;
        }

        // Filter kolom yang dikirim
        $fillable = explode(", ", Users::schemafillable());
        $data = array_intersect_key($input, array_flip($fillable));

        // Jalankan validator jika perlu
        $rules = [
            'username' => 'regex:/^[a-z0-9_]+$/|minlength:3',
            'email'    => 'email|unique:users,email,' . $id
        ];

        if (!Validator::validate($data, array_intersect_key($rules, $data))) {
            echo json_encode([
                'status'  => 'error',
                'message' => Validator::getErrorsString()
            ]);
            exit;
        }

        try {
            PembangunKueri::tabel(Users::schematable())
                ->dimana('id', $id)
                ->perbarui($data);

            echo json_encode([
                'status'  => 'success',
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Menghapus pengguna dari database.
     * Membutuhkan peran 'admin'.
     */
    public static function hapus($id_encrypted) {
        try {
            // Dekripsi ID terlebih dahulu
            $id = $id_encrypted;
            
            $data = PembangunKueri::tabel(Users::schematable())->dimana('id', $id)->danDimana('role', 'user')->hapus();
            
            if($data > 0){
                 $output_data = [
                    "status" => "success",
                    "message" => "Pengguna dengan ID {$id} berhasil dihapus.",
                ];
                Logcarbon::carbonlog(BASESESSION." :: deleted :: id: {$id}","userdata");
            }else{
                $output_data = [
                    "status" => "error",
                    "message" => "Pengguna dengan ID {$id} gagal untuk dihapus karena Role adalah Admin.",
                ];
                Logcarbon::carbonlog(BASESESSION." :: failed deleted :: id: {$id}","userdata");
            }
            
            // Set header JSON
            header('Content-Type: application/json; charset=utf-8');
            // HTTP 200 untuk sukses
            http_response_code(200); 
            echo json_encode($output_data, JSON_PRETTY_PRINT);
            exit();
        } catch (\Exception $e) {
            // Set header JSON
            header('Content-Type: application/json; charset=utf-8');
            $output_data = [
                "status" => "error",
                "message" => "Terjadi kesalahan: " . $e->getMessage(),
            ];
            
            // PERBAIKAN: Set status HTTP ke 500 agar ditangkap oleh fungsi error: di AJAX
            http_response_code(500); 
            echo json_encode($output_data, JSON_PRETTY_PRINT);
            exit();
        }
    }
}
