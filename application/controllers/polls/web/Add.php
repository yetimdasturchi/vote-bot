<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/web/Add_model','add');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
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
				base_url('assets/libs/select2/select2.min.css')
			],
			'load_js' => [
				base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.js'),
				base_url('assets/libs/datetimepicker/bootstrap-datepicker.'.getDefaultLanguage().'.js'),
				base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.js'),
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
			],
			'index_title' => lang('index_add'),
			'content' => 'polls/web/add'
		]);
	}

	public function get_polls(){
		$polls =  $this->add->get_polls();
		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($polls));
	}

}

/* End of file Add.php */
/* Location: ./application/controllers/polls/web/Add.php */