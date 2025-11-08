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
            'expired_at',
        ];
        return implode(", ", $fill); 
    }
    
    static public function schematable($table = "forgot_link") {
        return $table; 
    }
    
}