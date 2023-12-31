<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start_model extends CI_Model {
	public $module_name;
	public $args;
	public $config = [];

	public function __construct(){
		parent::__construct();
	}

	public function load( $args = [] ){
		$this->config = $this->module->load_config( $this->module_name );
		$this->load->register_views( $this->module_name );
		$this->lang->load_module($this->module_name, 'module');
		
		if ( !empty( $this->args[0] ) && method_exists( $this, $this->args[0] ) ) {
			$method = $this->args[0];
		}else{
			$method = 'settings';
		}

		$this->$method();
	}

	public function system(){


		$this->lang->load_module($this->module_name, 'systemm');
		$this->load->module_model($this->module_name, 'systemlang');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->module_systemlang->save_items();
		}

		$items = $this->module_systemlang->get_items();
		
		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_system',
			'content_data' => [
				'module_name' => $this->module_name,
				'items' => $items,
				'section' => lang('module_system')
			]
		]);
	}

	public function module(){
		$this->lang->load_module($this->module_name, 'systemm');
		$this->load->module_model($this->module_name, 'modulelang');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->module_modulelang->save_items();
		}

		$items = $this->module_modulelang->get_items();
		
		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_modules',
			'content_data' => [
				'module_name' => $this->module_name,
				'items' => $items,
				'args' => $this->args, 
				'section' => lang('module_modules')
			]
		]);
	}

	public function settings(){
		$this->lang->load_module($this->module_name, 'settings');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->save_settings();
		}
		
		$settings = $this->module->load_settings( $this->module_name, ['min' => 30, 'max' => 2000] );

		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_main',
			'content_data' => [
				'content' => 'module_settings',
				'content_data' => $settings,
				'module_name' => $this->module_name,
				'section' => lang('module_settings')
			]
		]);
	}
}

/* End of file Start_model.php */
/* Location: ./application/modules/lang/models/Start_model.php */