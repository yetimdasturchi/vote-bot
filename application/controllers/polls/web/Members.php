<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller {

	private $contest_id;
	private $nomination_id;

	private $content_query;
	private $nomination_query;

	private $action;

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/web/Members_model','members');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'polls',
			'dropify'
		]);

		$this->auth->check_acces('polls.edit');

	}

	public function _remap($method){
		$this->index();
	}

	public function index() {
		$this->contest_id = $this->uri->segment(4);
		$this->nomination_id = $this->uri->segment(5);
		$this->action = $this->uri->segment(6);

		if ( !empty( $this->contest_id ) && !empty( $this->nomination_id ) ) {
			$this->nomination_query = $this->db->get_where('nominations', [
				'id' => $this->nomination_id,
				'contest' => $this->contest_id,
			]);

			if ( $this->nomination_query->num_rows() > 0 ) {
				$this->nomination_query = $this->nomination_query->row_array();

				if ( !empty( $this->action ) ) {
					switch ($this->action) {
						case 'getlist':
							return $this->getlist();
						break;

						case 'add':
							return $this->add();
						break;

						case 'getnominations':
							return $this->getnominations();
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
					'index_title' => lang('polls_memebers'),
					'content' => 'polls/web/members',
					'content_data' => $this->nomination_query
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_nomination_not_found'));
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

		$memData = $this->members->getRows( $this->input->post() );
        
        $contest_id = $this->contest_id;
        $nomination_id = $this->nomination_id;
        $memData = array_map(function($x) use ( &$contest_id, &$nomination_id ){
        	
        	$image = base_url( 'uploads/members/noimage.png' );

			if ( !empty( $x['image'] ) ) {
			  if ( filter_var($x['image'], FILTER_VALIDATE_URL) ){
			    $image = $x['image'];
			  }else if( file_exists( FCPATH . 'uploads/members/' . $x['image'] ) ) {
			    $image = base_url( 'uploads/members/' . $x['image'] );
			  }
			}

        	$x['image'] = "<img src=\"".$image."\" width=\"32px\"  height=\"32px\">";

        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-success" href="${!${''} = base_url('polls/web/members/'.$contest_id.'/' . $nomination_id . '/stats/' . $x['id'] ) }"><i class="bx bx-stats"></i></a>
                <a class="btn btn-info" href="${!${''} = base_url('polls/web/members/'.$contest_id.'/' . $nomination_id . '/edit/' . $x['id']) }"><i class="bx bx-edit"></i></a>
                <button class="btn btn-danger" data-ajax-button="${!${''} = base_url('polls/web/members/'.$contest_id.'/'.$nomination_id . '/remove/' . $x['id']) }" data-message="${!${''} = lang('polls_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->members->countAll(),
            "recordsFiltered" => $this->members->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

	public function add(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->members->add( $this->contest_id, $this->nomination_id );
		}

		$this->load->view('manage/index', [
			'load_css' => [
				base_url('assets/libs/dropify/dropify.min.css'),
				base_url('assets/libs/select2/select2.min.css')
			],
			'load_js' => [
				base_url('assets/libs/dropify/dropify.min.js'),
				base_url('assets/libs/jquery-ui/jquery-ui.js'),
				base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
				base_url('assets/libs/repeater/jquery.repeater.min.js'),
				base_url('assets/libs/repeater/form-repeater.int.js'),
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
			],
			'index_title' => lang('index_add'),
			'content' => 'polls/web/members_add',
			'content_data' => [
				'nomination' => $this->nomination_query,
				'contest_id' => $this->contest_id,
				'nomination_id' => $this->nomination_id
			]
		]);
	}

	public function edit(){
		$id = $this->uri->segment(7);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('members', [
				'id' => $id
			]);

			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->members->edit( $this->contest_id, $this->nomination_id, $id, $query );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/dropify/dropify.min.css'),
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/dropify/dropify.min.js'),
						base_url('assets/libs/jquery-ui/jquery-ui.js'),
						base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
						base_url('assets/libs/repeater/jquery.repeater.min.js'),
						base_url('assets/libs/repeater/form-repeater.int.js'),
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
					],
					'index_title' => lang('index_add'),
					'content' => 'polls/web/members_edit',
					'content_data' => array_merge($query, [
						'selected_nominations' => $this->members->get_selected_nominations( $query['nomination'] )
					])
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('polls_member_not_found'));
				redirect(base_url('polls/web/members/'.$this->contest_id.'/'.$this->nomination_id.'/'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('polls_no_id_specified'));
			redirect(base_url('polls/web/members/'.$this->contest_id.'/'.$this->nomination_id.'/'));
		}
	}

	public function remove(){
		$id = $this->uri->segment(7);

		if ( !empty( $id ) ) {
			$query = $this->db->get_where('members', [
				'id' => $id
			]);

			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					$this->db->delete('members', ['id' => $id]);
					$this->db->delete('contest_votes', ['member' => $id]);

					if ( !empty( $query['image'] ) ) @unlink( FCPATH . 'uploads/members/' . $query['image'] );

					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(200)
				    	->set_output(json_encode([
				        	'status' => true,
				        	'reset' => false,
				        	'messages' => [
				        		'removed' => lang('poll_successfully_removed')
				        	],
				        	'_callback' => "function(){\$dtables['polls_web_members'].ajax.reload(null, false);}"
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
			        		lang('polls_member_not_found')
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

	public function getnominations(){
		$nominations =  $this->members->get_nominations();
		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output( json_encode( $nominations ) );
	}

}

/* End of file Members.php */
/* Location: ./application/controllers/polls/web/Members.php */