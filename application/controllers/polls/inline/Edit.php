<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/inline/Edit_model','edit');
		$this->load->model('xfields/Records_model','xfields');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls',
			'dropify'
		]);

		$this->auth->check_acces('polls.edit');

	}

	public function _remap($method){
		$this->index();
	}

	public function index(){
		$id = $this->uri->segment(4);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('poll_questions', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->edit->process( $id, $query );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.css'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.css'),
						base_url('assets/libs/dropify/dropify.min.css'),
					],
					'load_js' => [
						base_url('assets/libs/dropify/dropify.min.js'),
						base_url('assets/libs/jquery-ui/jquery-ui.js'),
						base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
						base_url('assets/libs/repeater/jquery.repeater.min.js'),
						base_url('assets/libs/repeater/form-repeater.int.js'),
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.js'),
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.'.getDefaultLanguage().'.js'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.js')
					],
					'index_title' => lang('index_edit'),
					'content' => 'polls/inline/edit',
					'content_data' => array_merge($query, [
						'xfields' => $this->xfields->get_columns()
					])
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

/* End of file Edit.php */
/* Location: ./application/controllers/polls/inline/Edit.php */