<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/Votes_model','votes');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->load->helper('text');

		$this->auth->check_acces('polls.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_votes'),
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
				base_url('assets/libs/select2/select2.min.css')
			],
			'load_js' => [
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
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
			'content' => 'polls/votes'
		]);
	}

	public function status($id='', $status = '0' ){
		$this->db->update('contest_votes', [
			'check_status' => $status
		], [
			'id' => $id
		]);

		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode([
	        	'status' => true,
	        	'messages' => [
	        		'removed' => lang('poll_data_successfully_updated')
	        	],
	        	'_callback' => "function(){\$dtables['votes'].ajax.reload(null, false);}"
	    	])
	    );
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

			$x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

        	$x['date'] = date($GLOBALS['system_config']['dateformat'], $x['date']);

        	if ( $x['check_status'] == '1' ) {
        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
            <a class="btn btn-warning" href="${!${''} = base_url('users/view/' . $x['u_id']) }" target="_blank"><i class="bx bx-info-circle"></i></a>
            </div>
            <div class="btn-group" role="group">
                <button class="btn btn-danger" data-ajax-button="${!${''} = base_url('polls/votes/status/' . $x['id'] . '/2') }" data-message="${!${''} = lang('polls_do_you_really_cancel') }"><i class="mdi mdi-close"></i></button>
            </div>
            EOL;	
        	}

        	if (  in_array($x['check_status'], ['2', '0']) ) {
        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
            <a class="btn btn-warning" href="${!${''} = base_url('users/view/' . $x['u_id']) }" target="_blank"><i class="bx bx-info-circle"></i></a>
            </div>
            <div class="btn-group" role="group">
                <button class="btn btn-success" data-ajax-button="${!${''} = base_url('polls/votes/status/' . $x['id'] . '/1') }" data-message="${!${''} = lang('polls_do_you_really_accept') }"><i class="mdi mdi-check"></i></button>
            </div>
            EOL;	
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

}

/* End of file Votes.php */
/* Location: ./application/controllers/polls/Votes.php */