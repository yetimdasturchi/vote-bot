<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infographic extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Infographic_model', 'infographic');

		$this->load->model('client/Stats_model', 'stats');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$nominations = $this->input->get('nomination');
		$members = $this->input->get('member');
		$status = $this->input->get('status');

		if ( !is_array( $nominations ) ) $nominations = [];
		if ( !is_array( $members ) ) $members = [];
		if ( !is_array( $status ) ) $status = [];

		$this->infographic->getvotes( $nominations, $members, $status );

		$this->load->view('client/index', [
			'index_title' => 'Infografika',
			'load_js' => [
				base_url('assets/libs/apexcharts.min.js'),
				base_url('assets/js/apexchart.js'),
				base_url('assets/libs/highmaps/highmaps.js'),
				base_url('assets/libs/highmaps/data.js'),
				base_url('assets/libs/highmaps/data.js'),
				base_url('assets/libs/screenshot/FileSaver.js'),
				base_url('assets/libs/screenshot/html2canvas.js')
			],
			'content' => 'client/infographic',
			'content_data' => [
				'all_users' => $this->infographic->getUsers(),
				'all_language' => $this->infographic->getlanguage(),
				'users_age' => $this->infographic->getUsersAge(),
				'cities' => $this->infographic->getCities(),
				'selected_nominations' => $nominations,
				'selected_members' => $members,
				'status' => $status,
			]
		]);
	}

}

/* End of file Infographic.php */
/* Location: ./application/controllers/client/Infographic.php */