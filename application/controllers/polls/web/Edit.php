<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/web/Edit_model','edit');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->auth->check_acces('polls.edit');

	}

	public function _remap($method){
		$this->index();
	}

	public function index(){
		$id = $this->uri->segment(4);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('contests', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->edit->process( $id, $query );
				}

				$selected_polls = $this->edit->get_selected_polls( $query['polls'] );
				$selected_polls_uzbek = $this->edit->get_selected_polls( $query['polls_uzbek'] );
				$selected_polls_uzbek_cyr = $this->edit->get_selected_polls( $query['polls_uzbek_cyr'] );
				$selected_polls_russian = $this->edit->get_selected_polls( $query['polls_russian'] );
				$selected_polls_english = $this->edit->get_selected_polls( $query['polls_english'] );

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.css'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.css'),
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.js'),
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.'.getDefaultLanguage().'.js'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.js'),
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
					],
					'index_title' => lang('index_edit'),
					'content' => 'polls/web/edit',
					'content_data' => array_merge(
						$query,
						[
							'selected_polls' => $selected_polls,
							'selected_polls_uzbek' => $selected_polls_uzbek,
							'selected_polls_uzbek_cyr' => $selected_polls_uzbek_cyr,
							'selected_polls_russian' => $selected_polls_russian,
							'selected_polls_english' => $selected_polls_english,
						]
					)
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_contest_not_found'));
				redirect(base_url('polls/web/records'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/web/records'));
		}
	}

}

/* End of file Edit.php */
/* Location: ./application/controllers/polls/web/Edit.php */