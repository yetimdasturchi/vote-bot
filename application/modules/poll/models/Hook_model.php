<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook_model extends CI_Model {

	public $module_name;

	public function load( $module_name ){
		$this->module_name = $module_name;
		$this->lang->load_module($this->module_name, 'bot');
	}

	public function run(){
		$this->telegram->send_message( 'run' );
	}

	public function process(){
		$this->telegram->send_message( 'process' );
	}

	public function back(){
		$this->user->active_function = '';
		$this->menu->back();
	}
}

/* End of file Hook.php */
/* Location: ./application/modules/functions/contact/models/Hook.php */