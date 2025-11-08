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
            'passsword',
            'active',
            'role',
        ];
        return implode(", ", $fill); 
    }
    
    static public function schematable($table = "master_users") {
        return $table; 
    }
    
}