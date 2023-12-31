<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {
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
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->notifications->edit_process( $id, $query );
				}

				$this->load->view('client/index', [
					'load_css' => [
						base_url('assets/libs/dropify/dropify.min.css'),
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/dropify/dropify.min.js'),
						base_url('assets/libs/jquery-ui/jquery-ui.js'),
						base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
						base_url('assets/libs/repeater/jquery.repeater.min.js'),
						base_url('assets/libs/repeater/form-repeater.int.js'),
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
					],
					'index_title' => 'Tahrirlash',
					'content' => 'client/notifications/edit',
					'content_data' => $query
				]);
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

}

/* End of file Edit.php */
/* Location: ./application/controllers/client/notifications/Edit.php */