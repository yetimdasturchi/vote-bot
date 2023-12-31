<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('notifications/Add_model','add');
		$this->load->model('users/Archive_model','archive');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'notifications',
			'dropify'
		]);

		$this->auth->check_acces('notifications.add');

	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->add->process();
		}

		$this->load->view('manage/index', [
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
			'index_title' => lang('index_add'),
			'content' => 'notifications/add'
		]);
	}

	public function get_users(){
		$polls =  $this->add->get_users();
		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($polls));
	}

}

/* End of file Add.php */
/* Location: ./application/controllers/notifications/Add.php */