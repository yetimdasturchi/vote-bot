<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/inline/Add_model','add');
		$this->load->model('xfields/Records_model','xfields');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls',
			'dropify'
		]);

		$this->auth->check_acces('polls.add');

	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->add->process();
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
			'index_title' => lang('index_add'),
			'content' => 'polls/inline/add',
			'content_data' => [
				'xfields' => $this->xfields->get_columns()
			]
		]);
	}

}

/* End of file Add.php */
/* Location: ./application/controllers/polls/inline/Add.php */