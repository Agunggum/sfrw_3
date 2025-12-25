<?php if ( ! defined('MODPATH')) exit('No direct script access allowed');
$filelogs = (!isset($_GET['file'])) ?  "error.log":$_GET['file'].".log";
$getfile = fopen('logs/'.$filelogs, 'r');
$result = [];

if ($getfile) {
    while ($data = fgets($getfile)) {
        $parts = explode(' ~ ', trim($data));
        
        // Pastikan jumlah elemen sesuai sebelum memasukkan ke array
        if (count($parts) >= 3) {
            $result[] = [
                'waktu'   => $parts[0],
                'level'   => $parts[1].', '.$parts[2],
                'pesan'   => $parts[3]
            ];
        }
    }
    fclose($getfile);
}

echo json_encode($result, JSON_PRETTY_PRINT);