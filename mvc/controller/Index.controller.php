<?php

class IndexController extends Controller {

    public static function index() {
        require_once tampilan('index', [
            $data['title'] = "sfrw Framework",
        ]);
    }

    public static function Lang() {
        $kamus = file_get_contents('public/kamus.txt');
        $struktur = file_get_contents('public/struktur_bahasa.txt');
        echo $kamus;
    }

    public static function updateLang() {
        $inputData = json_decode(file_get_contents('php://input'), true);

        if ($inputData && isset($inputData['kamus'])) {
            $pathKamus = 'public/kamus.txt'; // SELEKSI: Sesuaikan dengan jalur file kamus.txt Anda
            
            // 2. Baca isi kamus lama yang sudah ada di server
            $kamusLama = [];
            if (file_exists($pathKamus)) {
                $isiFile = file_get_contents($pathKamus);
                $kamusLama = json_decode($isiFile, true) ?: [];
            }

            // Pastikan struktur dasar id dan en ada pada kamus lama
            if (!isset($kamusLama['id'])) $kamusLama['id'] = [];
            if (!isset($kamusLama['en'])) $kamusLama['en'] = [];

            $kamusBaruDariFrontend = $inputData['kamus'];

            // 3. KUNCI PERBAIKAN: Gunakan operator + atau array_merge 
            // untuk menambahkan yang BELUM ADA saja tanpa menimpa yang sudah di-update manual.
            // Kamus Lama ditaruh di depan agar nilainya (termasuk hasil edit manual) tidak tertimpa!
            $kamusGabungan = [
                'id' => $kamusLama['id'] + ($kamusBaruDariFrontend['id'] ?? []),
                'en' => $kamusLama['en'] + ($kamusBaruDariFrontend['en'] ?? [])
            ];

            // 4. Simpan kembali hasil penggabungan ke kamus.txt
            if (file_put_contents($pathKamus, json_encode($kamusGabungan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Kamus berhasil diperbarui dengan data baru."]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Gagal menulis ke file kamus.txt"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Data tidak valid."]);
        }
    }

    public static function file_contents_save($filename, $data) {
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

}
