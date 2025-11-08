<?php
require_once model('login');

use app\Models\Users;
use sfrw\logcarbon;

class Logincontroller extends Controller {

    public static function loginform($uri){
        $loginModel = new Loginmodel;
        return $loginModel->loginformmodel($uri);
    }

    public static function forgotform($uri){
        $loginModel = new Loginmodel;
        return $loginModel->forgotformmodel($uri);
    }

     public static function forgotnewform($uri,$s){
        $loginModel = new Loginmodel;
        return $loginModel->forgotnewformmodel($uri,$s);
    }
   
    static public function signout($nik,$log){
        $loginModel = new Loginmodel;
        return $loginModel->signoutmodel($nik,$log);
    }
    
    static public function namelog($field){
        $cek = permintaanMysql("SELECT ".Users::schemafillable()." FROM ".Users::schematable()." WHERE nik = '".BASESESSION."'");
        $j   = mysqlAmbilArray($cek);
        
        $fillabled = explode(", ", Users::schemafillable());
        for($ar=1; $ar<count($fillabled); $ar++){
            if($field == $fillabled[$ar]){ return $j[$fillabled[$ar]]; }
        }
    }

}