<?php
namespace app\Models;

use muhammad\contracs\container\Model;

class Users extends Model {

    //schema table users
    static public function schemafillable() {
        $fill = [
            'id',
            'fullname',
            'email',
            'username',
            'password',
            'active',
            'role',
        ];
        return implode(", ", $fill); 
    }
    
    static public function schematable($table = "master_users") {
        return $table; 
    }

    static public function skema() {
        return "CREATE TABLE IF NOT EXISTS " . self::schematable() . " (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `fullname` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `username` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `active` enum('Y','N') NOT NULL DEFAULT 'Y',
            `role` varchar(50) NOT NULL DEFAULT 'user',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    }
    
}