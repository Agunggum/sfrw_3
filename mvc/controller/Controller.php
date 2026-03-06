<?php

class Indexcontroller extends Controller {

    public static function index() {
        require_once tampilan('index', [
            $data['title'] = "sfrw Framework",
        ]);
    }

}