<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('notifications/Records_model','records');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'notifications'
		]);

		$this->auth->check_acces('notifications.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_nitifications'),
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
			],
			'load_js' => [
				base_url('assets/libs/datatables/jquery.dataTables.min.js'),
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.js'),
				base_url('assets/libs/datatables/dataTables.buttons.min.js'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.js'),
				base_url('assets/libs/datatables/jszip.min.js'),
				base_url('assets/libs/datatables/pdfmake.min.js'),
				base_url('assets/libs/datatables/vfs_fonts.js'),
				base_url('assets/libs/datatables/buttons.html5.min.js'),
				base_url('assets/libs/datatables/buttons.print.min.js'),
				base_url('assets/libs/datatables/buttons.colVis.min.js'),
				base_url('assets/libs/datatables/dataTables.responsive.min.js'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.js'),
				base_url('assets/js/dtable.js'),
			],
			'content' => 'notifications/records'
		]);
	}

	public function getlist(){
		$postData = $this->input->post();

		if ( !empty( $postData ) ) {
			$notifications = glob( FCPATH . 'tmp/notifications/m_*.json');
			$notifications_count = count( $notifications );

			usort( $notifications, function( $a, $b ) { return filemtime($a) - filemtime($b); } );
			$notifications = array_reverse( $notifications );

			$notifications = array_slice( $notifications, $postData['start'], $postData['length'] ); 

			$result = [
        		'draw' => $this->input->post('draw'),
        		'iTotalRecords' => $notifications_count,
        		'iTotalDisplayRecords' => $notifications_count,
        		'aaData' => $this->records->render( $notifications ),
    		];
		}else{
			$result = [
        		'draw' => $this->input->post('draw'),
        		'iTotalRecords' => 0,
        		'iTotalDisplayRecords' => 0,
        		'aaData' => [],
    		];
		}

		echo json_encode($result);
	}

	public function clear(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->records->clear();
		}
	}

	public function play(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->records->play();
		}
	}

	public function pause(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->records->pause();
		}
	}

}

/* End of file Records.php */
/* Location: ./application/controllers/notifications/Records.php */