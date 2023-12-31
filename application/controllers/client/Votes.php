<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Votes_model', 'votes');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$status = $this->input->get('status');

		if( !is_array( $status ) ) {
			if ( !empty( $status ) ) {
				$status = [ $status ];
			}else{
				$status = [];
			}
		}

		$this->load->view('client/index', [
			'index_title' => "Ovozlar",
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
			'content' => 'client/votes',
			'content_data' => [
				'status' => $status
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
		
        $memData = $this->votes->getRows( $this->input->post() );
        
        $memData = array_map(function($x){
        	if ( !empty( $x['u_first_name'] ) ) {
				$u_name = $x['u_first_name'];        		
        	}

        	if ( !empty( $x['u_last_name'] ) ) {
        		$u_name .= $x['u_last_name'];
        	}

        	if ( !empty( $x['u_username'] ) ) {
				$u_name .= " ( @{$x['u_username']} )";
			}
			
			$u_name = trim( $u_name );

			if ( mb_strlen( $u_name, "UTF-8" ) > 52 ) {
				$u_name = "Excepted";
			}

			if ( !empty( $x['u_phone'] ) ) {
        		$x['u_phone'] = format_phone( $x['u_phone'] );
        	}else{
        		$x['u_phone'] = "<code>-</code>";
        	}

			$x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

        	$x['date'] = date("d.m.Y (H:i)", $x['date']);

        	switch ( $x['check_status'] ) {
        		case '1':
        			$x['check_status'] = "<span class=\"status-badge-success\"></span> Tekshirilgan";
        		break;

        		case '2':
        			$x['check_status'] = "<span class=\"status-badge-danger\"></span> Bekor qilingan";
        		break;
        		
        		default:
        			$x['check_status'] = "<span class=\"status-badge-process\"></span> Jarayonda";
        		break;
        	}

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->votes->countAll(),
            "recordsFiltered" => $this->votes->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

    public function export(){
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$vstatus = $this->input->post('vstatus');
			$vnomination = $this->input->post('vnomination');
			$vmember = $this->input->post('vmember');
			$term = $this->input->post('term');

			return $this->votes->exportData( $vstatus, $vnomination, $vmember, $term );
		}

		$filename = $this->input->get('filename');
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ( $ext == 'xlsx' && file_exists( FCPATH . 'tmp/'.$filename ) ) {
			$this->load->helper('download');
			force_download(FCPATH . 'tmp/'.$filename, NULL, FALSE, TRUE);
		}
	}

}

/* End of file Votes.php */
/* Location: ./application/controllers/client/Votes.php */