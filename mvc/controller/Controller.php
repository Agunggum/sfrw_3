<?php

class Indexcontroller extends Controller {

    public static function index() {
        require_once view('index', [
            $data['title'] = "sfrw Framework",
        ]);
    }

}