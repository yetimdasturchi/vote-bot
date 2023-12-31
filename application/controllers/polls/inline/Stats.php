<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/inline/Stats_model','stats');
		
		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->auth->check_acces('polls.view');

		$this->load->library('calendar');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$id = $this->uri->segment(4);
		$action = $this->uri->segment(5);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('poll_questions', ['id' => $id]);
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ( !empty( $action ) ) {
					switch ($action) {
						case 'getlist':
							return $this->getlist( $id );
						break;
					}
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
						base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
						base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
					],
					'load_js' => [
						base_url('assets/libs/apexcharts/apexcharts.min.js'),
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
						base_url('assets/js/apexchart.js'),
					],
					'index_title' => lang('poll_stats'),
					'content' => 'polls/inline/stats',
					'content_data' => [
						'id' => $id,
						'poll_question' => $query,
						'poll_answers' => $this->stats->get_answers($id),
						'poll_series' => $this->stats->get_series($id)
					]
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_not_found'));
				redirect(base_url('polls/inline/records'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/inline/records'));
		}
	}

	public function getlist( $id ){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$memData = $this->stats->getRows( $this->input->post() );
        
        $memData = array_map(function($x) use(&$id){
        	
        	$name = $x['first_name'] . ' '. $x['last_name'];
			if ( !empty( $x['username'] ) ) {
				$name .= " ( @{$x['username']} )";
			}
			if ( strlen( $name ) > 52 ) {
				$name = "Excepted";
			}
			$x['user'] = $name;

        	$x['date'] = date( $GLOBALS['system_config']['dateformat'], $x['date'] );

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->stats->countAll(),
            "recordsFiltered" => $this->stats->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

}

/* End of file Stats.php */
/* Location: ./application/controllers/polls/inline/Stats.php */