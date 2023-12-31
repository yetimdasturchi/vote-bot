<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remove extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Notifications_model', 'notifications');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$id = $this->uri->segment(4);
		if ( !empty( $id ) ) {
			$query = $this->db->get_where('notifications_templates', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					$this->db->delete('notifications_templates', ['id' => $id]);
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(200)
				    	->set_output(json_encode([
				        	'status' => true,
				        	'reset' => false,
				        	'messages' => [
				        		'removed' => "Andoza muvaffaqiyatli o'chirildi"
				        	],
				        	'_callback' => "function(){\$dtables['client_nitifications_templates'].ajax.reload(null, false);}"
				    	])
				    );
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(405)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'messages' => [
				        		"Ruxsat etilmagan metod"
				        	]
				    	])
				    );
				}
			}else{
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(404)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		"Andoza topilmadi"
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
		        		"Idenfikator ko'rsatilmadi"
		        	]
		    	])
		    );
		}
	}

}

/* End of file Remove.php */
/* Location: ./application/controllers/client/notifications/Remove.php */