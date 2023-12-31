<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nominations extends CI_Controller {

	private $id;
	private $query;
	private $action;

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/web/Nominations_model','nominations');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls'
		]);

		$this->auth->check_acces('polls.edit');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$this->id = $this->uri->segment(4);
		$this->action = $this->uri->segment(5);

		if ( !empty( $this->id ) ) {
			$this->query = $this->db->get_where('contests', ['id' => $this->id]);
			if ( $this->query->num_rows() > 0 ) {
				$this->query = $this->query->row_array();

				if ( !empty( $this->action ) ) {
					switch ($this->action) {
						case 'getlist':
							return $this->getlist();
						break;

						case 'add':
							return $this->add();
						break;

						case 'edit':
							return $this->edit();
						break;

						case 'remove':
							return $this->remove();
						break;
					}
					
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
						base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
						base_url('assets/libs/datatables/responsive.bootstrap4.min.css')
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
					'index_title' => lang('polls_nominations'),
					'content' => 'polls/web/nominations',
					'content_data' => $this->query
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_contest_not_found'));
				redirect(base_url('polls/web/records'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/web/records'));
		}
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$memData = $this->nominations->getRows( $this->input->post() );
        
        $id = $this->id;
        $memData = array_map(function($x) use ( $id ){
        	
        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-success" href="${!${''} = base_url('polls/web/stats/' . $x['id']) }"><i class="bx bx-stats"></i></a>
                <a class="btn btn-secondary" href="${!${''} = base_url('polls/web/members/' . $x['contest'] . '/' . $x['id']) }"><i class="bx bx-list-plus"></i></a>
                <a class="btn btn-info" href="${!${''} = base_url('polls/web/nominations/' . $id . '/edit/' . $x['id']) }"><i class="bx bx-edit"></i></a>
                <button class="btn btn-danger" data-ajax-button="${!${''} = base_url('polls/web/nominations/' . $id . '/remove/' . $x['id']) }" data-message="${!${''} = lang('polls_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->nominations->countAll(),
            "recordsFiltered" => $this->nominations->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

	public function add(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->nominations->add( $this->id );
		}

		$this->load->view('manage/index', [
			'load_css' => [
				base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.css'),
				base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.css'),
				base_url('assets/libs/select2/select2.min.css')
			],
			'load_js' => [
				base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.js'),
				base_url('assets/libs/datetimepicker/bootstrap-datepicker.'.getDefaultLanguage().'.js'),
				base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.js'),
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
			],
			'index_title' => lang('index_add'),
			'content' => 'polls/web/nominations_add',
			'content_data' => $this->query
		]);
	}

	public function edit(){
		$id = $this->uri->segment(6);
		$query = $this->db->get_where('nominations', ['id' => $id]);
		if( !empty( $id ) ) {	
			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->nominations->edit( $this->id, $id );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.css'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.css'),
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.min.js'),
						base_url('assets/libs/datetimepicker/bootstrap-datepicker.'.getDefaultLanguage().'.js'),
						base_url('assets/libs/datetimepicker/bootstrap-timepicker.min.js'),
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
					],
					'index_title' => lang('index_edit'),
					'content' => 'polls/web/nominations_edit',
					'content_data' => [
						'nomination' => $query,
						'contest' => $this->query
					]
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_nomination_not_found'));
				redirect(base_url( 'polls/web/nominations/' . $this->id ));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/web/nominations/' . $this->id ));
		}
	}

	public function remove(){
		$id = $this->uri->segment(6);
		$query = $this->db->get_where('nominations', ['id' => $id]);
		if( !empty( $id ) ) {	
			if ( $query->num_rows() > 0 ) {
				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					$this->db->delete('nominations', ['id' => $id]);
					$this->db->delete('contest_votes', ['nomination' => $id]);
					$this->db->delete('members', ['nomination' => $id]);

					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(200)
				    	->set_output(json_encode([
				        	'status' => true,
				        	'reset' => false,
				        	'messages' => [
				        		'removed' => lang('poll_successfully_removed')
				        	],
				        	'_callback' => "function(){\$dtables['polls_web_nominations'].ajax.reload(null, false);}"
				    	])
				    );
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(405)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'messages' => [
				        		lang('polls_method_not_allowed')
				        	]
				    	])
				    );
				}
			}else{
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('polls_nomination_not_found')
			        	]
			    	])
			    );
			}
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => [
		        		lang('polls_no_id_specified')
		        	]
		    	])
		    );
		}
	}

}

/* End of file Nominations.php */
/* Location: ./application/controllers/polls/web/Nominations.php */