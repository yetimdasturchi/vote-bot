<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/web/Records_model','records');

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
			'index_title' => lang('index_polls') . ' ('. lang('polls_web') .')',
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
			'content' => 'polls/web/records'
		]);
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$memData = $this->records->getRows( $this->input->post() );
        
        $memData = array_map(function($x){
        	
        	if ( $x['expire'] > 0 ) {
        		$x['expire'] = date($GLOBALS['system_config']['dateformat'], $x['expire']);
        	}else{
        		$x['expire'] = "<code>".lang('polls_unlimited')."</code>";
        	}

        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-success" href="${!${''} = base_url('polls/web/stats/' . $x['id']) }"><i class="bx bx-stats"></i></a>
                <a class="btn btn-secondary" href="${!${''} = base_url('polls/web/nominations/' . $x['id']) }"><i class="bx bx-list-plus"></i></a>
                <a class="btn btn-info" href="${!${''} = base_url('polls/web/edit/' . $x['id']) }"><i class="bx bx-edit"></i></a>
                <button class="btn btn-danger" data-ajax-button="${!${''} = base_url('polls/web/remove/' . $x['id']) }" data-message="${!${''} = lang('polls_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->records->countAll(),
            "recordsFiltered" => $this->records->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

}

/* End of file Records.php */
/* Location: ./application/controllers/polls/web/Records.php */