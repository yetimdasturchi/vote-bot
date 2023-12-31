<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/inline/Send_model','send');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->auth->check_acces('polls.view');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$id = $this->uri->segment(4);
		$action = $this->uri->segment(5);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('poll_questions', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ( !empty( $action ) ) {
					switch ($action) {
						case 'channels':
							return $this->send->getChannels();
						break;
					}
				}

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->send->process( $id );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
					],
					'index_title' => lang('polls_channels'),
					'content' => 'polls/inline/send',
					'content_data' => [
						'id' => $id,
						'poll_question' => $query
					]
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_not_found'));
				redirect(base_url('polls/inline/records'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/inline/records'));
		}
	}

}

/* End of file Send.php */
/* Location: ./application/controllers/polls/inline/Send.php */