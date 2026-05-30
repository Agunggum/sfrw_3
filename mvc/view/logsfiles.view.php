<?php if ( ! defined('MODPATH')) exit('No direct script access allowed');
$filelogs = (!isset($file)) ? "error.log" : $file . ".log";
$getfile = fopen('logs/' . $filelogs, 'r');
$result = [];

if ($getfile) {
    while ($data = fgets($getfile)) {
        $parts = explode(' ~ ', trim($data));
        
        // Pastikan jumlah elemen sesuai sebelum memasukkan ke array
        if (count($parts) >= 4) { // Diubah ke >= 4 karena Anda memanggil $parts[3]
            $result[] = [
                'waktu'   => daydateandtime_indo($parts[0]),
                'level'   => $parts[1] . ', ' . $parts[2],
                'pesan'   => $parts[3]
            ];
        }
    }
    fclose($getfile);
}

// Tambahkan baris ini untuk membalikkan urutan dari bawah ke atas
$result = array_reverse($result);

echo json_encode($result, JSON_PRETTY_PRINT);