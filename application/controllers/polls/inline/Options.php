<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/inline/Options_model','options');

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

						case 'update_field':
							return $this->update_field( $id );
						break;

						case 'addanswer':
							return $this->addanswer( $id );
						break;

						case 'remove':
							if ($this->input->server('REQUEST_METHOD') === 'POST') {
								$this->db->delete('poll_answers', ['id' => $id]);
								$this->db->delete('poll', ['answer_id' => $id]);
								return $this->output
							    	->set_content_type('application/json')
							    	->set_status_header(200)
							    	->set_output(json_encode([
							        	'status' => true,
							        	'reset' => false,
							        	'messages' => [
							        		'removed' => lang('poll_successfully_removed')
							        	],
							        	'_callback' => "function(){\$dtables['polls_inline_options'].ajax.reload(null, false);}"
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
						break;
					}
					
				}

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->edit->process( $id, $query );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
						base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
						base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
						base_url('assets/libs/editable/editable.css'),
					],
					'load_js' => [
						base_url('assets/libs/editable/index.js'),
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
					'index_title' => lang('poll_options'),
					'content' => 'polls/inline/options',
					'content_data' => $query
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

	public function update_field(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->options->update_field();
		}else{
			return $this->output
    		->set_content_type('application/json')
    		->set_status_header(405)
    		->set_output(json_encode([
    			'status' => false,
                'messages' => [lang('polls_method_not_allowed')]
    		]));
		}
	}

	public function addanswer( $id ){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->options->addanswer( $id );
		}else{
			return $this->output
    		->set_content_type('application/json')
    		->set_status_header(405)
    		->set_output(json_encode([
    			'status' => false,
                'messages' => [lang('polls_method_not_allowed')]
    		]));
		}
	}

	public function getlist( $id ){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$memData = $this->options->getRows( $this->input->post() );
        
        $memData = array_map(function($x) use(&$id){
        	
        	$x['answer'] = "<a href=\"javascript: void(0);\" data-pk=\"{$x['id']}\" class=\"inline-answer\">{$x['answer']}</a>";

        	$x['action'] = <<<EOL
            <div class="btn-group" role="group">
                <button class="btn btn-danger" data-ajax-button="${!${''} = base_url('polls/inline/options/'.$id.'/remove/' . $x['id']) }" data-message="${!${''} = lang('polls_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->options->countAll(),
            "recordsFiltered" => $this->options->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

}

/* End of file Options.php */
/* Location: ./application/controllers/polls/inline/Options.php */