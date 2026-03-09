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
            $config = fileCon(BASEPATH);
            $driver = $config[4] ?? 'MySqli';

            if ($driver == 'MySqli') {
                self::$koneksi = new mysqli($config[0], $config[1], $config[2], $config[3]);
                if (self::$koneksi->connect_error) {
                    throw new Exception("Koneksi gagal: " . self::$koneksi->connect_error);
                }
            } else {
                self::$koneksi = mysql_connect($config[0], $config[1], $config[2]);
                if (!self::$koneksi) {
                    throw new Exception('Tidak bisa terhubung ke MySQL');
                }
                mysql_select_db($config[3], self::$koneksi);
            }
        }
        return self::$koneksi;
    }

    protected static function getDriver() {
        $config = fileCon(BASEPATH);
        return $config[4] ?? 'MySqli';
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

    protected function bangunKueriSelect() {
        $kueri = "SELECT {$this->pilih} FROM {$this->tabel}";
        if (!empty($this->gabung)) {
            $kueri .= ' ' . implode(' ', $this->gabung);
        }
        if (!empty($this->dimana)) {
            $kueri .= " WHERE " . implode(' AND ', $this->dimana);
        }
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
        if (!empty($this->dimana)) {
            $kueri .= " WHERE " . implode(' AND ', $this->dimana);
        }
        return $this->eksekusi($kueri);
    }

    public function hapus() {
        $kueri = "DELETE FROM {$this->tabel}";
        if (!empty($this->dimana)) {
            $kueri .= " WHERE " . implode(' AND ', $this->dimana);
        }
        return $this->eksekusi($kueri);
    }
}
