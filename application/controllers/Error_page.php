<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_page extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load( 'error');

	}

	public function index() {
		$this->load->view('manage/error');
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */