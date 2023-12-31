<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remove extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('xfields/Records_model','records');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'xfields'
		]);

		$this->auth->check_acces('users.view');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => [
		        		lang('xfields_method_not_allowed')
		        	]
		    	])
		    );
		}

		$field = $this->uri->segment(3);

		if ( !empty( $field ) ) {
			$fields = $this->db->list_fields('additional_fields');

			if ( in_array($field, $fields) ) {
				$this->load->dbforge();
				$this->dbforge->drop_column('additional_fields', $field);

				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(200)
			    	->set_output(json_encode([
			        	'status' => true,
			        	'reset' => false,
			        	'messages' => [
			        		'removed' => lang('xfield_successfully_removed')
			        	],
			        	'_callback' => "function(){\$dtables['xfields_records'].ajax.reload(null, false);}"
			    	])
			    );  	
			}else{
				return $this->output
		    		->set_content_type('application/json')
		    		->set_status_header(400)
		    		->set_output(json_encode([
		        		'status' => false,
		        		'messages' => [
		        			lang('xfields_field_not_found')
		        		]
		    		])
		    	);
			}
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => [
		        		lang('xfields_no_field_specified')
		        	]
		    	])
		    );
		}
	}

}

/* End of file Remove.php */
/* Location: ./application/controllers/xfields/Remove.php */