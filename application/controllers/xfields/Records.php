<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('xfields/Records_model','records');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'xfields'
		]);

		$this->auth->check_acces('users.view');

	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_xfields'),
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
			'content' => 'xfields/records'
		]);
	}

	public function getlist(){
		
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$postData = $this->input->post();

		if ( !empty( $postData ) ) {
			$fields = $this->records->get_columns();
			$fields_count = count( $fields );

			$fields = array_map(function($x){
				$x['action'] = <<<EOL
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-danger" data-ajax-button="${!${''} = base_url('xfields/remove/' . $x['xfield']) }" data-message="${!${''} = lang('xfields_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
                    </div>
                EOL;
				return $x;
			}, $fields);

			$result = [
        		'draw' => $this->input->post('draw'),
        		'iTotalRecords' => $fields_count,
        		'iTotalDisplayRecords' => $fields_count,
        		'aaData' => $fields,
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

}

/* End of file Records.php */
/* Location: ./application/controllers/xfields/Records.php */