<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Notifications_model', 'notifications');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->notifications->add_process();
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
			'index_title' => 'Bildirishnomalar',
			'content' => 'client/notifications/add'
		]);
	}

}

/* End of file Add.php */
/* Location: ./application/controllers/client/notifications/Add.php */