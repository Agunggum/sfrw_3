<?php 
namespace muhammad\foundation;

class Application
{
     public static function publicPath()
    {
        foreach (glob("app/Models/*".EXT) as $filenamemodels)
        {
            require_once $filenamemodels;
        }
        foreach (glob("mvc/controller/*".EXT) as $filenamecontrollers)
        {
            require_once $filenamecontrollers;
        }
        define('VERSIONFRMAEWORK', '3.0');
        require_once core('Core');
    }
}