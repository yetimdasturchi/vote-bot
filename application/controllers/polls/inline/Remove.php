<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remove extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->auth->check_acces('polls.delete');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$id = $this->uri->segment(4);
		if ( !empty( $id ) ) {
			$query = $this->db->get_where('poll_questions', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					$this->db->delete('poll_questions', ['id' => $id]);
					$this->db->delete('poll_answers', ['question_id' => $id]);
					$this->db->delete('poll', ['question_id' => $id]);
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(200)
				    	->set_output(json_encode([
				        	'status' => true,
				        	'reset' => false,
				        	'messages' => [
				        		'removed' => lang('poll_successfully_removed')
				        	],
				        	'_callback' => "function(){\$dtables['polls_inline_records'].ajax.reload(null, false);}"
				    	])
				    );
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(405)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'messages' => [
				        		lang('polls_method_not_allowed')
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
			        		lang('polls_not_found')
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
		        		lang('polls_no_id_specified')
		        	]
		    	])
		    );
		}
	}

}

/* End of file Delete.php */
/* Location: ./application/controllers/polls/inline/Delete.php */