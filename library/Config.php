<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db {

	static public function connectMySQL($base) {
        // Menggunakan koneksi dari Container.php untuk konsistensi
        return get_db_conn();
	}

	static public function closeConnectMySQL($base) {
        if ($GLOBALS['sfrw_db_conn'] instanceof mysqli) {
            $GLOBALS['sfrw_db_conn']->close();
            $GLOBALS['sfrw_db_conn'] = null;
        }
	}

}
//instance object database
$db = new db();
/* End of file config.php */
/* Location: ./library/config.php */
