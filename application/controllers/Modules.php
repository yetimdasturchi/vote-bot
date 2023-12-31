<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'modules'
		]);

		$this->auth->check_acces('modules.view');

	}

	public function _remap(){
		$this->load( func_get_args() );
	}

	public function load( $args ){
		$module = $args[0];
		if ( $this->load->is_module_exists( $module ) ) {
			$this->load->module_model($module, 'start');
			$this->module_start->module_name = $module;
			$this->module_start->args = ( !empty( $args[1] ) ? $args[1] : [] );
			$this->module_start->load();
		}else{
			show_404();
		}
	}

}

/* End of file Modules.php */
/* Location: ./application/controllers/bot/Modules.php */