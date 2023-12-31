<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Referrals_model', 'referrals');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$this->load->view('client/index', [
			'index_title' => "Referallar",
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
			'content' => 'client/referrals'
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
		
        $memData = $this->referrals->getRows( $this->input->post() );

        $memData = array_map(function($x){
        	$ci = & get_instance();

        	$x['o_name'] = "";

        	if ( !empty( $x['o_first_name'] ) ) {
				$u_name = $x['o_first_name'];        		
        	}

        	if ( !empty( $x['o_last_name'] ) ) {
        		$u_name .= $x['o_last_name'];
        	}

        	if ( !empty( $x['o_username'] ) ) {
				$u_name .= " ( @{$x['o_username']} )";
			}
			
			$u_name = trim( $u_name );

			if ( mb_strlen( $u_name, "UTF-8" ) > 52 ) {
				$u_name = "Excepted";
			}

			if ( !empty( $x['o_phone'] ) ) {
        		$x['o_phone'] = format_phone( $x['o_phone'] );
        	}else{
        		$x['o_phone'] = "<code>-</code>";
        	}

			$x['o_name'] = !empty( $u_name ) ? $u_name : $x['owner_id'];
			$x['votes'] = "<span class=\"status-badge-process\"></span> {$x['process']} / <span class=\"status-badge-danger\"></span>  {$x['ignored']} / <span class=\"status-badge-success\"></span> {$x['success']}";

			$x['action'] = <<<EOL
                <button type="button" data-open-modal="${!${''} = base_url('client/users/referrals/' . $x['owner_id']) }" data-modal-title="Ro'yxat" class="btn btn-outline-secondary btn-sm waves-light mb-1 mx-1">Ro'yxat</button><br />
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
        	"draw" => $this->input->post('draw'),
            "recordsTotal" => $this->referrals->countAll(),
            "recordsFiltered" => $this->referrals->countAll(),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

}

/* End of file Referrals.php */
/* Location: ./application/controllers/client/Referrals.php */