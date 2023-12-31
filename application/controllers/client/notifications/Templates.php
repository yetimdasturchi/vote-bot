<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Notifications_model', 'notifications');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$this->load->view('client/index', [
			'index_title' => "Andozalar",
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
			'content' => 'client/notifications/templates',
			'content_data' => [
				'ct' => $this->notifications->get_ct(),
				'gender' => $this->notifications->get_gender(),
			]
		]);
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->notifications->getRows( $this->input->post() );

        $memData = array_map(function($x){
        	if ( $x['date'] > 0 ) {
        		$x['date'] = date('d.m.Y (H:i)', $x['date']);
        	}else{
        		$x['date'] = "<code>-</code>";
        	}

        	if ( $x['last_send'] > 0 ) {
        		$x['last_send'] = date('d.m.Y (H:i)', $x['last_send']);
        	}else{
        		$x['last_send'] = "<code>-</code>";
        	}

        	$x['action'] = <<<EOL
                <button type="button" data-send-notification="{$x['id']}" class="btn btn-outline-primary btn-sm waves-light mb-1 mx-1">Yuborish</button><br />
                <a href="${!${''} = base_url('client/notifications/edit/' . $x['id']) }" class="btn btn-outline-warning btn-sm waves-light mb-1 mx-1">Tahrirlash</a><br />
                <button type="button" data-ajax-button="${!${''} = base_url('client/notifications/remove/' . $x['id']) }" data-message="Siz chindan ham ushbu andozani o'chirmoqchimisiz?" class="btn btn-outline-danger btn-sm waves-light mx-1">O'chirish</button>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->notifications->countAll(),
            "recordsFiltered" => $this->notifications->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

	public function get_users(){
		$polls =  $this->notifications->get_users();
		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($polls));
	}

	public function send(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->notifications->send();
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => [
		        		"Ruxsat etilmagan metod"
		        	]
		    	])
		    );
		}
	}

}

/* End of file Records.php */
/* Location: ./application/controllers/client/notifications/Records.php */