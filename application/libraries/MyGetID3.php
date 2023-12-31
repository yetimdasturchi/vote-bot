<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyGetID3 {
    public function __construct() {
        require_once APPPATH.'third_party/getID3/getid3/getid3.php';
    }
}