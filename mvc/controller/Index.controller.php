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
        $inputData = file_get_contents('php://input');

        if (!empty($inputData)) {
            $data = json_decode($inputData, true);
            
            if ($data !== null) {
                // 1. Simpan file kamus.txt (Hasil auto-translate Indonesia & Inggris)
                if (isset($data['kamus'])) {
                    self::file_contents_save('public/kamus.txt', $data['kamus']);
                }
                
                // 2. Simpan file struktur_bahasa.txt (Log perubahan Class & ID)
                if (isset($data['struktur'])) {
                    self::file_contents_save('public/struktur_bahasa.txt', $data['struktur']);
                }

                echo "Sukses: Kamus diterjemahkan & Struktur diperbarui!";
            } else {
                echo "Gagal: Format JSON tidak valid.";
            }
        }
    }

    public static function file_contents_save($filename, $data) {
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

}
