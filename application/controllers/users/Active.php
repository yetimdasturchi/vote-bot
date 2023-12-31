<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('users/Active_model','active');
		$this->load->model('xfields/Records_model','xfield');

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
			'index_title' => lang('index_users'),
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
				base_url('assets/js/tableToExcel.js'),
			],
			'content' => 'users/active'
		]);
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->active->getRows( $this->input->post() );
        
        $memData = array_map(function($x){
        	
        	$name = "";

        	if ( !empty( $x['first_name'] ) ) {
				$name = $x['first_name'];        		
        	}

        	if ( !empty( $x['last_name'] ) ) {
        		$name .= $x['last_name'];
        	}

        	if ( !empty( $x['username'] ) ) {
				$name .= " ( @{$x['username']} )";
			}
			
			$name = trim( $name );

			if ( mb_strlen( $name, "UTF-8" ) > 52 ) {
				$name = "Excepted";
			}

			$x['phone'] = empty( $x['phone'] ) ? "<code>".lang('users_undefined')."</code>" : format_phone($x['phone']);
			
			$x['username'] = !empty( $name ) ? $name : $x['chat_id'];

        	$x['registered'] = date($GLOBALS['system_config']['dateformat'], $x['registered']);
        	$x['last_action'] = date($GLOBALS['system_config']['dateformat'], $x['last_action']);

        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-warning" href="${!${''} = base_url('users/view/' . $x['id']) }"><i class="bx bx-info-circle"></i></a>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->active->countAll(),
            "recordsFiltered" => $this->active->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

    public function export(){
    	$data['users'] = $this->active->export();
    	$data['xfields'] = $this->xfield->get_columns();
    	$html = $this->load->view('export/users', $data, TRUE);

    	return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'html' => $html
            ])
        );
    }

}

/* End of file Active.php */
/* Location: ./application/controllers/users/Active.php */