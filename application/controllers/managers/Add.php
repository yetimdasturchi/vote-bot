<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('managers/Add_model','add');

		$this->config->load('managers');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'managers'
		]);

		$this->auth->check_acces('managers.add');

	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->add->process();
		}

		$this->load->view('manage/index', [
			'index_title' => lang('index_add'),
			'content' => 'managers/add'
		]);
	}

}

/* End of file Add.php */
/* Location: ./application/controllers/managers/Add.php */