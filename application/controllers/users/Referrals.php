<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('users/Referrals_model','referrals');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'users'
		]);

		$this->auth->check_acces('users.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_referrals'),
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
			'content' => 'users/referrals'
		]);
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->referrals->getRows( $this->input->post() );
        
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

			$x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

			if ( !empty( $x['o_first_name'] ) ) {
				$o_name = $x['o_first_name'];        		
        	}

        	if ( !empty( $x['o_last_name'] ) ) {
        		$o_name .= $x['o_last_name'];
        	}

        	if ( !empty( $x['o_username'] ) ) {
				$o_name .= " ( @{$x['o_username']} )";
			}
			
			$o_name = trim( $o_name );

			if ( mb_strlen( $o_name, "UTF-8" ) > 52 ) {
				$o_name = "Excepted";
			}

			$x['o_name'] = !empty( $o_name ) ? $o_name : $x['owner_id'];


        	$x['date'] = date($GLOBALS['system_config']['dateformat'], $x['date']);
        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->referrals->countAll(),
            "recordsFiltered" => $this->referrals->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

}

/* End of file Referrals.php */
/* Location: ./application/controllers/users/Referrals.php */