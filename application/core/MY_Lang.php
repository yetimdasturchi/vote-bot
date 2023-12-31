<?php

class MY_Lang extends CI_Lang {

    function __construct() {
        parent::__construct();
    }

    public function pack( $langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '' ){
    	if ( empty( $idiom ) ) {
            $idiom = !empty( get_cookie('language') ) ? get_cookie('language') : config_item('language');
        }
    	$this->load($langfile, $idiom, $return, $add_suffix, $alt_path);
    }

    public function load_module( $module, $langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '' ){
    	$module_path = APPPATH . 'modules/' . $module . '/';
        if ( empty( $idiom ) ) {
    		$idiom = !empty( get_cookie('language') ) ? get_cookie('language') : config_item('language');
    	}
		$this->load($langfile, $idiom, $return, $add_suffix, $module_path);
    }
}