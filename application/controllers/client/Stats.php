<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Stats_model', 'stats');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$this->load->view('client/index', [
			'index_title' => 'Statistika',
			'load_js' => [
				base_url('assets/libs/apexcharts.min.js'),
				base_url('assets/js/apexchart.js'),
				base_url('assets/libs/highmaps/highmaps.js'),
				base_url('assets/libs/highmaps/data.js')
			],
			'content' => 'client/stats',
			'content_data' => [
				'all_votes' => $this->stats->getVotes(),
				'all_votes_group' => $this->stats->getVotesGroup(),
				'all_users' => $this->stats->getUsers(),
				'all_language' => $this->stats->getlanguage(),
				'weekly_users' => $this->stats->getWeeklyUsers(),
				'monthly_users' => $this->stats->getMonthlyUsers(),
				'monthly_votes' => $this->stats->getMonthlyVotes(),
				'users_age' => $this->stats->getUsersAge(),
				'cities' => $this->stats->getCities()
			]
		]);
	}

}

/* End of file Test.php */
/* Location: ./application/controllers/client/Test.php */