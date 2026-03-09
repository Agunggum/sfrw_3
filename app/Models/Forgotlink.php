<?php
namespace app\Models;

use muhammad\contracs\container\Model;

class Forgotlink extends Model {

    //schema table users
    static public function schemafillable() {
        $fill = [
            'id',
            'email',
            'target_link',
            'end_time',
        ];
        return implode(", ", $fill); 
    }
    
    static public function schematable($table = "forgot_link") {
        return $table; 
    }

    static public function skema() {
        return "CREATE TABLE IF NOT EXISTS `forgot_link` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(255) NOT NULL,
            `target_link` varchar(255) NOT NULL,
            `end_time` datetime NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    }
    
}