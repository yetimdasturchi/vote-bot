<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

	}

	public function index() {
		redirect( base_url( 'stats/audit' ) );
	}

}

/* End of file Manage.php */
/* Location: ./application/controllers/Manage.php */