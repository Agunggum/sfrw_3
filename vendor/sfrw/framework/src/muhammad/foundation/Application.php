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
        foreach (glob("mvc/controller/*.controller".EXT) as $filenamecontrollers)
        {
            require_once $filenamecontrollers;
        }
        foreach (glob("mvc/model/*.model".EXT) as $filenamemodels)
        {
            require_once $filenamemodels;
        }
        foreach (glob("app/Http/Middleware/*".EXT) as $filenamemiddleware)
        {
            require_once $filenamemiddleware;
        }
        define('VERSIONFRMAEWORK', '3.0');
        require_once core('Core');
    }

    public static function rutePath()
    {
        require_once route('route');
    }
}