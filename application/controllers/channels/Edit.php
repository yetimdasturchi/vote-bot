<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('channels/Edit_model','edit');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'channels'
		]);

		$this->auth->check_acces('channels.edit');
	}

	public function _remap($method){
		$this->index();
	}

	public function index(){
		$id = $this->uri->segment(3);
		if ( !empty( $id ) ) {
			$query = $this->db->get_where('channels', ['channel_id' => $id]);
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->edit->process( $id );
				}

				$this->load->view('manage/index', [
					'index_title' => lang('index_edit'),
					'content' => 'channels/edit',
					'content_data' => $query
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('channel_not_found'));
				redirect(base_url('managers/records'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('channel_no_id_specified'));
			redirect(base_url('managers/records'));
		}
	}

}

/* End of file Edit.php */
/* Location: ./application/controllers/channels/Edit.php */