<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Notifications_model', 'notifications');
		$this->load->model('client/Notificationsrecords_model', 'records');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$this->load->view('client/index', [
			'index_title' => "Jarayon",
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
				base_url('assets/libs/select2/select2.min.css')
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
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
			],
			'content' => 'client/notifications/records'
		]);
	}

	public function getlist(){
		//ini_set('memory_limit', -1);
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->records->getRows( $this->input->post() );
        $memData = array_map(function($x){
        	$ci = & get_instance();

        	$x['u_name'] = "";

        	if( $x['source'] == '1' ){
        		if ( !empty( $x['first_name'] ) ) {
					$x['u_name'] .= $x['first_name'];        		
	        	}

	        	if ( !empty( $x['last_name'] ) ) {
	        		$x['u_name'] .= $x['last_name'];
	        	}

	        	if ( !empty( $x['username'] ) ) {
					$x['u_name'] .= " ( @{$x['username']} )";
				}
        	}else if( $x['source'] == '0' ){
        		if ( !empty( $x['users_archive_users_name'] ) ) {
					$x['u_name'] = $x['users_archive_users_name'];        		
	        	}
        	}

        	if ( empty( $x['u_name'] ) ) {
        		$x['u_name'] = $x['chat_id'];
        	}

        	if ( $x['date'] > 0 ) {
        		$x['date'] = date('d.m.Y (H:i)', $x['date']);
        	}else{
        		$x['date'] = "<code>-</code>";
        	}

        	$x['type'] = 'Matn';

			//$x['message'] = "<span data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"".htmlentities( $x['message'] )."\"><span class=\"badge bg-secondary cursor-pointer\">Ko'rish</span></span>";
			$x['message'] = "";
			$x['user'] = "";
        	return $x;
        }, $memData);
        
        $output = array(
        	"draw" => $this->input->post('draw'),
            "recordsTotal" => $this->records->countAll(),
            "recordsFiltered" => $this->records->countAll(),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

	public function clear(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->notifications->clear();
		}
	}

	public function play(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->notifications->play();
		}
	}

	public function pause(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->notifications->pause();
		}
	}

}

/* End of file Records.php */
/* Location: ./application/controllers/client/notifications/Records.php */