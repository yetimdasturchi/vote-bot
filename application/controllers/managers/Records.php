<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('managers/Records_model','records');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'managers'
		]);

		$this->auth->check_acces('managers.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_managers'),
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
			'content' => 'managers/records'
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
        	
        	$x['manager_created'] = !empty( $x['manager_created'] ) ? date( $GLOBALS['system_config']['dateformat'], $x['manager_created'] ) : "<span class=\"badge bg-danger\">".lang('manager_unidentified')."</span>";

        	$x['manager_logged'] = !empty( $x['manager_logged'] ) ? date( $GLOBALS['system_config']['dateformat'], $x['manager_logged'] ) : "<span class=\"badge bg-danger\">".lang('manager_unidentified')."</span>";

        	$x['manager_telegram'] = "<code>{$x['manager_telegram']}</code>";

        	$x['manager_action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-outline-info" href="${!${''} = base_url('managers/edit/' . $x['manager_id']) }"><i class="bx bx-edit"></i></a>
                <button class="btn btn-outline-danger" data-ajax-button="${!${''} = base_url('managers/remove/' . $x['manager_id']) }" data-message="${!${''} = lang('managers_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
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

/* End of file List.php */
/* Location: ./application/controllers/managers/List.php */