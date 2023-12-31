<?php

class MY_Loader extends CI_Loader {

    private $ci;

    function __construct() {
        parent::__construct();
        $this->ci =& get_instance();
    }

    public function is_model_loaded($name){
        return in_array($name, $this->_ci_models, TRUE);
    }

    public function is_module_exists( $fn ){
        return file_exists( APPPATH . 'modules/' . $fn . '/models/Start_model.php' );
    }

    public function is_telegram_module_exists( $fn ){
        return file_exists( APPPATH . 'modules/' . $fn . '/models/Hook_model.php' );
    }

    public function module_model( $fn, $name = '' ){
        $this->ci->load->model( '../modules/' . $fn . '/models/' . ucfirst( $name ) . '_model', 'module_'.$name );
    }

    public function register_views( $fn ){
        $this->_ci_view_paths = array_merge($this->_ci_view_paths, array(APPPATH . 'modules/'.$fn.'/views/' => TRUE));
    }
}