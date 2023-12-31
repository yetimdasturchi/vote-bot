<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('stats/Audit_model', 'audit');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'audit'
		]);

		$this->auth->check_acces('stats.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'load_js' => [
				base_url('assets/libs/apexcharts.min.js'),
				base_url('assets/js/pages/dashboard.init.js')
			],
			'content' => 'stats/audit',
			'content_data' => [
				'all_users' => $this->audit->getUsers(),
				'all_votes' => $this->audit->getVotes(),
				'all_votes_fixed' => $this->audit->getVotesFixed(),
			]
		]);
	}

}

/* End of file Audit.php */
/* Location: ./application/controllers/stats/Audit.php */