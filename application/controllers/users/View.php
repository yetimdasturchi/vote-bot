<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {

	private $id;
	private $query;
	private $action;

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('users/View_model','view');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'users'
		]);

		$this->auth->check_acces('users.view');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$this->id = $this->uri->segment(3);
		$this->action = $this->uri->segment(4);

		if ( !empty( $this->id ) ) {
			$this->query = $this->db->get_where('users', ['id' => $this->id]);
			if ( $this->query->num_rows() > 0 ) {
				$this->query = $this->query->row_array();

				if ( !empty( $this->action ) && method_exists($this, $this->action) ) {
					$action = $this->action;
					return $this->$action();
				}

				$this->load->view('manage/index', [
					'index_title' => lang('index_users'),
					'content' => 'users/view',
					'content_data' => array_merge(
						$this->query,
						[
							'referrals' => $this->view->get_referrals_count( $this->query['chat_id'] )
						]
					)
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('users_not_found'));
				redirect(base_url('users/active'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('users_no_id_specified'));
			redirect(base_url('users/active'));
		}
	}

	public function additional() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->view->update_additional();
		}

		$this->load->view('manage/index', [
			'index_title' => lang('index_users'),
			'load_css' => [
				base_url('assets/libs/editable/editable.css')
			],
			'load_js' => [
				base_url('assets/libs/editable/index.js')
			],
			'content' => 'users/view_additional',
			'content_data' => array_merge(
				$this->query,
				[
					'additionals' => $this->view->get_additional_info( $this->id )
				]
			)
		]);
	}

	public function inline(){
		$this->load->view('manage/index', [
			'index_title' => lang('index_users'),
			'content' => 'users/view_inline',
			'content_data' => $this->query
		]);
	}

	public function web(){
		$this->load->view('manage/index', [
			'index_title' => lang('index_users'),
			'content' => 'users/view_web',
			'content_data' => $this->query
		]);
	}

	public function referrals(){
		$this->load->view('manage/index', [
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
			],
			'load_js' => [
				base_url('assets/libs/datatables/jquery.dataTables.min.js'),
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.js'),
				base_url('assets/libs/datatables/dataTables.buttons.min.js'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.js'),
				base_url('assets/libs/datatables/jszip.min.js'),
				base_url('assets/libs/datatables/pdfmake.min.js'),
				base_url('assets/libs/datatables/vfs_fonts.js'),
				base_url('assets/libs/datatables/buttons.html5.min.js'),
				base_url('assets/libs/datatables/buttons.print.min.js'),
				base_url('assets/libs/datatables/buttons.colVis.min.js'),
				base_url('assets/libs/datatables/dataTables.responsive.min.js'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.js'),
				base_url('assets/js/dtable.js'),
			],
			'index_title' => lang('index_users'),
			'content' => 'users/view_referrals',
			'content_data' => $this->query
		]);
	}

}

/* End of file View.php */
/* Location: ./application/controllers/users/View.php */