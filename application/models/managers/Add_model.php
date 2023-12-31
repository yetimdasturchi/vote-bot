<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function process(){
		$this->set_rules();
		
		if ( $this->form_validation->run() ) {
			$managers_permissions = config_item('managers_permissions');
			$modules = $this->input->post('modules');
			$permissions = [];

			
			foreach (config_item('managers_permissions') as $k => $v) {
				if ( array_key_exists($k, $modules) && is_array( $modules[$k] ) ) {
					foreach ($v as $kk => $vv) {
						if ( array_key_exists($kk, $modules[$k]) && is_string( $modules[$k][$kk] ) && $modules[$k][$kk] == 'on' ) {
							$permissions[$k][$kk] = true;
						}else{
							$permissions[$k][$kk] = $vv;
						}
					}
				}else{
					$permissions[$k] = $v;
				}
			}

			$this->db->insert('managers', [
				'manager_telegram' => $this->input->post('telegram'),
				'manager_name' => $this->input->post('name'),
				'manager_created' => time(),
				'manager_modules' => json_encode($permissions),
				'manager_status' => $this->input->post('status'),
			]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
		        	'reset' => true,
		        	'messages' => [
		        		'addedd' => lang('manages_successfully_added')
		        	],
		        	'_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('managers/records')."' }, 1000);}"
		    	])
		    );
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => $this->form_validation->error_array()
		    	])
		    );
		}
	}

	public function set_rules(){
		$this->form_validation->set_rules('name', lang('manager_name'), 'required|alpha_numeric_spaces|min_length[4]|max_length[255]');
		$this->form_validation->set_rules('telegram', lang('manager_telegram'), 'required|min_length[4]|max_length[32]|is_unique[managers.manager_telegram]');
		$this->form_validation->set_rules('status', lang('manager_status'), 'required|in_list[0,1]');
		
		$this->form_validation->set_rules('modules[]', lang('manager_moduls'), 'required', [
			'required' => lang('manager_moduls_required')
		]);
	}
}

/* End of file Add_model.php */
/* Location: ./application/models/managers/Add_model.php */