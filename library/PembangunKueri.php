<?php

class PembangunKueri {
    protected static $koneksi;
    protected $tabel;
    protected $pilih = '*';
    protected $gabung = [];
    protected $dimana = [];
    protected $urutkan = '';
    protected $batas = '';
    protected $mulai = '';

    protected static function hubungkan() {
        if (!self::$koneksi) {
            $driver = DB_DRIVER;

            if ($driver == 'MySqli') {
                if (!class_exists('mysqli')) {
                    throw new Exception("Ekstensi MySQLi tidak terinstal di server ini.");
                }
                self::$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                if (self::$koneksi->connect_error) {
                    throw new Exception("Koneksi MySQLi gagal: " . self::$koneksi->connect_error);
                }
            } else {
                if (!function_exists('mysql_connect')) {
                    throw new Exception("Ekstensi MySQL lama (mysql_*) tidak didukung di versi PHP ini. Gunakan driver 'MySqli'.");
                }
                self::$koneksi = mysql_connect(DB_HOST, DB_USER, DB_PASS);
                if (!self::$koneksi) {
                    throw new Exception('Koneksi MySQL gagal: ' . mysql_error());
                }
                mysql_select_db(DB_NAME, self::$koneksi);
            }
        }
        return self::$koneksi;
    }

    protected static function getDriver() {
        return DB_DRIVER;
    }

    protected static function escape($nilai) {
        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            return $koneksi->real_escape_string($nilai);
        } else {
            return mysql_real_escape_string($nilai, $koneksi);
        }
    }

    protected function formatNilai($nilai) {
        if (is_null($nilai)) {
            return "NULL";
        }
        if (is_bool($nilai)) {
            return $nilai ? "1" : "0";
        }
        if (is_numeric($nilai)) {
            return $nilai;
        }
        return "'" . self::escape($nilai) . "'";
    }

    public static function tabel($namaTabel) {
        $instance = new self();
        $instance->tabel = $namaTabel;
        return $instance;
    }

    public static function kueriMentah($kueri) {
        $instance = new self();
        return $instance->eksekusi($kueri);
    }

    public static function cekTabel($namaTabel) {
        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            $hasil = $koneksi->query("SHOW TABLES LIKE '{$namaTabel}'");
            return $hasil && $hasil->num_rows > 0;
        } else {
            $hasil = mysql_query("SHOW TABLES LIKE '{$namaTabel}'", $koneksi);
            return $hasil && mysql_num_rows($hasil) > 0;
        }
    }

    public function pilih(...$kolom) {
        $kolom_str = implode(', ', $kolom);
        if ($this->pilih === '*' || $this->pilih === '') {
            $this->pilih = $kolom_str;
        } else {
            $this->pilih .= ', ' . $kolom_str;
        }
        return $this;
    }

    public function gabung($tabel, $kolom1, $operator = null, $kolom2 = null) {
        if (is_null($operator)) {
            // Jika hanya 2 argumen, mungkin kueri join mentah
            $this->gabung[] = "JOIN {$tabel} ON {$kolom1}";
        } else {
            if (is_null($kolom2)) {
                $kolom2 = $operator;
                $operator = '=';
            }
            $this->gabung[] = "JOIN {$tabel} ON {$kolom1} {$operator} {$kolom2}";
        }
        return $this;
    }

    public function dimana($kolom, $operator = null, $nilai = null) {
        if (is_array($kolom)) {
            foreach ($kolom as $k => $v) {
                $this->dimana($k, '=', $v);
            }
            return $this;
        }

        // Jika hanya 2 argumen, asumsikan operatornya adalah '='
        if (func_num_args() === 2) {
            $nilai = $operator;
            $operator = '=';
        }
        
        $nilai = $this->formatNilai($nilai);
        $this->dimana[] = "{$kolom} {$operator} {$nilai}";
        return $this;
    }

    public function danDimana($kolom, $operator = null, $nilai = null) {
        if (is_array($kolom)) {
            foreach ($kolom as $k => $v) {
                $this->danDimana($k, '=', $v);
            }
            return $this;
        }

        if (func_num_args() === 2) {
            $nilai = $operator;
            $operator = '=';
        }

        $nilai = $this->formatNilai($nilai);
        $this->dimana[] = "AND {$kolom} {$operator} {$nilai}";
        return $this;
    }

    public function atauDimana($kolom, $operator = null, $nilai = null) {
        if (is_array($kolom)) {
            foreach ($kolom as $k => $v) {
                $this->atauDimana($k, '=', $v);
            }
            return $this;
        }

        if (func_num_args() === 2) {
            $nilai = $operator;
            $operator = '=';
        }

        $nilai = $this->formatNilai($nilai);
        $this->dimana[] = "OR {$kolom} {$operator} {$nilai}";
        return $this;
    }

    public function urutkan($kolom, $arah = 'ASC') {
        $this->urutkan = "ORDER BY {$kolom} {$arah}";
        return $this;
    }

    public function batas($jumlah) {
        $this->batas = "LIMIT {$jumlah}";
        return $this;
    }

    public function mulai($offset) {
        $this->mulai = "OFFSET {$offset}";
        return $this;
    }

    public function reset() {
        $this->pilih = '*';
        $this->gabung = [];
        $this->dimana = [];
        $this->urutkan = '';
        $this->batas = '';
        $this->mulai = '';
        return $this;
    }

    protected function bangunWhere() {
        $kueri = "";
        if (!empty($this->dimana)) {
            $kueri .= " WHERE ";
            foreach ($this->dimana as $i => $kondisi) {
                if ($i > 0) {
                    // Cek apakah kondisi sudah diawali dengan OR atau AND
                    if (strpos(strtoupper($kondisi), 'OR ') !== 0 && strpos(strtoupper($kondisi), 'AND ') !== 0) {
                        $kueri .= " AND ";
                    } else {
                        $kueri .= " ";
                    }
                }
                $kueri .= $kondisi;
            }
        }
        return $kueri;
    }

    protected function bangunKueriSelect() {
        $kueri = "SELECT {$this->pilih} FROM {$this->tabel}";
        if (!empty($this->gabung)) {
            $kueri .= ' ' . implode(' ', $this->gabung);
        }
        $kueri .= $this->bangunWhere();
        if ($this->urutkan) {
            $kueri .= " {$this->urutkan}";
        }
        if ($this->batas) {
            $kueri .= " {$this->batas}";
        }
        if ($this->mulai) {
            $kueri .= " {$this->mulai}";
        }
        return $kueri;
    }

    protected function eksekusi($kueri) {
        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            $hasil = $koneksi->query($kueri);
            if (!$hasil) {
                throw new Exception("Error kueri: " . $koneksi->error . " (Kueri: {$kueri})");
            }
            return $hasil;
        } else {
            $hasil = mysql_query($kueri, $koneksi);
            if (!$hasil) {
                throw new Exception("Error kueri: " . mysql_error() . " (Kueri: {$kueri})");
            }
            return $hasil;
        }
    }

    public function dapatkan() {
        $kueri = $this->bangunKueriSelect();
        $hasil_query = $this->eksekusi($kueri);
        $hasil = [];
        if (self::getDriver() == 'MySqli') {
            while ($baris = $hasil_query->fetch_assoc()) {
                $hasil[] = $baris;
            }
        } else {
            while ($baris = mysql_fetch_assoc($hasil_query)) {
                $hasil[] = $baris;
            }
        }
        return $hasil;
    }

    public function pertama() {
        $original_batas = $this->batas;
        $this->batas(1);
        $hasil = $this->dapatkan();
        $this->batas = $original_batas; // Restore original limit
        return $hasil[0] ?? null;
    }

    public function sisipkan($data) {
        $kolom = implode(', ', array_keys($data));
        $nilai = array_map(function($val) {
            return $this->formatNilai($val);
        }, array_values($data));
        $nilai = implode(', ', $nilai);
        $kueri = "INSERT INTO {$this->tabel} ({$kolom}) VALUES ({$nilai})";
        $this->eksekusi($kueri);
        
        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            return $koneksi->insert_id;
        } else {
            return mysql_insert_id($koneksi);
        }
    }

    public function perbarui($data) {
        $set = [];
        foreach ($data as $kolom => $nilai) {
            $nilai = $this->formatNilai($nilai);
            $set[] = "{$kolom} = {$nilai}";
        }
        $set = implode(', ', $set);
        $kueri = "UPDATE {$this->tabel} SET {$set}";
        $kueri .= $this->bangunWhere();
        
        $this->eksekusi($kueri);

        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            return $koneksi->affected_rows;
        } else {
            return mysql_affected_rows($koneksi);
        }
    }

    public function hapus() {
        $kueri = "DELETE FROM {$this->tabel}";
        $kueri .= $this->bangunWhere();
        
        $this->eksekusi($kueri);

        $koneksi = self::hubungkan();
        if (self::getDriver() == 'MySqli') {
            return $koneksi->affected_rows;
        } else {
            return mysql_affected_rows($koneksi);
        }
    }
}
