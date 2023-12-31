<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->dbforge();
	}

	public function process(){
		$this->set_rules();

		if ( $this->form_validation->run() ) {
			$fields = $this->db->list_fields('additional_fields');
			
			$xfield = $this->input->post('xfield');
			$name = $this->input->post('name');

			if ( in_array( $xfield, $fields) ) {
				return $this->output
		    		->set_content_type('application/json')
		    		->set_status_header(400)
		    		->set_output(json_encode([
		        		'status' => false,
		        		'messages' => [
		        			lang('xfields_already_exists')
		        		]
		    		])
		    	);
		    	exit(0);
			}

			$this->dbforge->add_column('additional_fields', [
				$xfield => [
					'type' => 'varchar',
					'constraint' => 255,
                	'comment' => $name
				]
			]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
		        	'messages' => [
		        		lang('xfields_successfully_added')
		        	],
		        	'_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('xfields/records')."' }, 1000);}"
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
		$this->form_validation->set_rules('name', lang('xfields_name'), 'required|alpha_numeric_spaces');
		$this->form_validation->set_rules('xfield', lang('xfields_xfield'), 'required|alpha|min_length[3]|max_length[255]');
	}
}

/* End of file Add_model.php */
/* Location: ./application/models/xfields/Add_model.php */