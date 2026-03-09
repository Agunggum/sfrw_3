<?php
use app\Models\Users;
use app\Models\Forgotlink;

class DatabaseSeederController extends Controller {

    private const KUNCI_RAHASIA = 'seeding-sfrw'; // Ganti dengan kunci yang lebih aman

    public function jalankan($kunci) {
        // 1. Keamanan: Hanya berjalan di lingkungan lokal
        if (ENVIRONMENT !== 'local') {
            header("HTTP/1.0 403 Forbidden");
            die('Akses ditolak. Fitur ini hanya aktif di lingkungan lokal.');
        }

        // 2. Keamanan: Periksa kunci rahasia
        if ($kunci !== self::KUNCI_RAHASIA) {
            header("HTTP/1.0 403 Forbidden");
            die('Akses ditolak. Kunci rahasia tidak valid.');
        }

        // 3. Konfirmasi Pengguna (JavaScript)
        $is_fresh = isset($_GET['fresh']) && $_GET['fresh'] === 'ya';
        if (!isset($_GET['konfirmasi']) || $_GET['konfirmasi'] !== 'ya') {
            $this->tampilkanHalamanKonfirmasi();
            return;
        }

        // Jika dikonfirmasi, jalankan prosesnya
        echo "<pre>";
        try {
            // Matikan pengecekan foreign key untuk sementara
            PembangunKueri::kueriMentah("SET FOREIGN_KEY_CHECKS = 0");

            if ($is_fresh) {
                $this->hapusSemuaTabel();
            }
            $this->buatTabel();
            $this->isiData();

            // Aktifkan kembali pengecekan foreign key
            PembangunKueri::kueriMentah("SET FOREIGN_KEY_CHECKS = 1");
            
            echo "\nProses setup database selesai.";
        } catch (\Exception $e) {
            echo "\n[ERROR FATAL] " . $e->getMessage();
        }
        echo "</pre>";
    }

    private function tampilkanHalamanKonfirmasi() {
        $tabel_ada = false;
        try {
            $tabel_ada = $this->cekTabelAda();
        } catch (\Exception $e) {
            // Jika koneksi gagal atau database tidak ada, kita anggap tabel belum ada
            // Namun jika user ingin setup, mereka mungkin perlu membuat database manual dulu
            // tergantung konfigurasi host mereka.
        }
        
        $url_base = $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?');
        
        if ($tabel_ada) {
            echo "<script>
                if (confirm('PERINGATAN KRITIS: Database sudah berisi data/tabel. Apakah Anda ingin melakukan FRESH INSTALL? \\n\\nKlik OK untuk RESET (Hapus semua data & tabel lama) dan mulai dari awal. \\n\\nHati-hati, data yang dihapus tidak bisa dikembalikan!')) {
                    window.location.href = '{$url_base}konfirmasi=ya&fresh=ya';
                } else {
                    if (confirm('Apakah Anda hanya ingin menjalankan migrasi tambahan tanpa menghapus data lama?')) {
                        window.location.href = '{$url_base}konfirmasi=ya';
                    } else {
                        alert('Proses dibatalkan.');
                        window.history.back();
                    }
                }
            </script>";
        } else {
            echo "<script>
                if (confirm('PERINGATAN: Anda akan membuat struktur database baru. Lanjutkan?')) {
                    window.location.href = '{$url_base}konfirmasi=ya';
                } else {
                    alert('Proses dibatalkan.');
                    window.history.back();
                }
            </script>";
        }
    }

    private function cekTabelAda() {
        // Cek minimal satu tabel utama, misal master_users
        return PembangunKueri::cekTabel(Users::schematable());
    }

    private function hapusSemuaTabel() {
        echo "Mereset database (Fresh Install)...\n";
        
        $path_model = "app/Models/*" . EXT;
        $files = glob($path_model);
        
        foreach ($files as $file) {
            $file_basename = basename($file, EXT);
            $nama_kelas = 'app\\Models\\' . $file_basename;

            if (class_exists($nama_kelas) && method_exists($nama_kelas, 'schematable')) {
                $tabel = $nama_kelas::schematable();
                try {
                    PembangunKueri::kueriMentah("DROP TABLE IF EXISTS `{$tabel}`");
                    echo "- Tabel `{$tabel}` berhasil dihapus.\n";
                } catch (\Exception $e) {
                    echo "- Gagal menghapus tabel `{$tabel}`: " . $e->getMessage() . "\n";
                }
            }
        }
        echo "Reset selesai.\n\n";
    }

    private function buatTabel() {
        echo "Membuat tabel secara dinamis...\n";

        $path_model = "app/Models/*" . EXT;
        $files = glob($path_model);
        
        if (empty($files)) {
            echo "- Tidak ada file model ditemukan di app/Models/\n";
            return;
        }

        foreach ($files as $file) {
            $file_basename = basename($file, EXT);
            $nama_kelas = 'app\\Models\\' . $file_basename;

            // Periksa apakah kelas ada (sudah di-load oleh Application.php)
            if (class_exists($nama_kelas)) {
                if (method_exists($nama_kelas, 'skema')) {
                    try {
                        PembangunKueri::kueriMentah($nama_kelas::skema());
                        echo "- Tabel untuk model '{$nama_kelas}' berhasil diproses.\n";
                    } catch (\Exception $e) {
                        echo "- Gagal memproses tabel untuk model '{$nama_kelas}': " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "- Model '{$nama_kelas}' tidak memiliki metode skema(). Skip.\n";
                }
            } else {
                echo "- Kelas '{$nama_kelas}' tidak ditemukan. Pastikan nama file sesuai dengan nama kelas.\n";
            }
        }

        echo "Pembuatan tabel selesai.\n\n";
    }

    private function isiData() {
        echo "Mengisi data awal (jika diperlukan)...\n";

        try {
            // Periksa apakah admin sudah ada
            $tabel_users = Users::schematable();
            $admin = PembangunKueri::tabel($tabel_users)->dimana('username', '=', 'admin')->pertama();

            if (!$admin) {
                PembangunKueri::tabel($tabel_users)->sisipkan([
                    'fullname' => 'Administrator',
                    'email' => 'admin@sfrw.com',
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT), // Ganti dengan password yang lebih kuat
                    'active' => 'Y',
                    'role' => 'admin'
                ]);
                echo "- Pengguna admin berhasil dibuat (username: admin, password: admin123).\n";
            } else {
                echo "- Pengguna admin sudah ada, tidak ada data baru yang ditambahkan.\n";
            }
        } catch (\Exception $e) {
            echo "- Gagal mengisi data: " . $e->getMessage() . "\n";
        }

        echo "Pengisian data selesai.\n";
    }
}
