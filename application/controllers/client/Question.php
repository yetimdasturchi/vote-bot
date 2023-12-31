<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Question_model', 'question');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$ids = $this->input->get('id');

		if( !is_array( $ids ) ) {
			if ( !empty( $ids ) ) {
				$ids = [ $ids ];
			}else{
				$ids = [];
			}
		}

		$this->load->view('client/index', [
			'index_title' => "Savollar",
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
			'content' => 'client/question',
			'content_data' => [
				'ids' => $ids
			]
		]);
	}

	public function status( $id ){
		$send_date = time() + rand(0, 120);

		$this->db->update('contest_queue', [
			'expire' => $send_date + 64800,
			'send_date' => $send_date,
			'sended' => '0',
		], [
			'id' => $id
		]);

		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode([
	        	'status' => true,
	        	'messages' => [
	        		'removed' => "Savol qayta yuborish jarayoniga qo'shildi"
	        	],
	        	'_callback' => "function(){\$dtables['client_questions'].ajax.reload(null, false);}"
	    	])
	    );
	}

	public function resend(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}

		$poll_ids = $this->input->post('poll_ids');
		$term = $this->input->post('term');

		return $this->question->resend( $poll_ids, $term );
	}

	public function export(){
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$poll_ids = $this->input->post('poll_ids');
			$qstatus = $this->input->post('qstatus');
			$term = $this->input->post('term');

			return $this->question->exportData( $poll_ids, $qstatus, $term );
		}

		$filename = $this->input->get('filename');
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ( $ext == 'xlsx' && file_exists( FCPATH . 'tmp/'.$filename ) ) {
			$this->load->helper('download');
			force_download(FCPATH . 'tmp/'.$filename, NULL, FALSE, TRUE);
		}
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->question->getRows( $this->input->post() );
        
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

			if ( !empty( $x['uz_name'] ) ) {
				$question = $x['uz_name'];
			}else if ( !empty( $x['uzc_name'] ) ) {
				$question = $x['uzc_name'];
			}else if ( !empty( $x['ru_name'] ) ) {
				$question = $x['ru_name'];
			}else if ( !empty( $x['en_name'] ) ) {
				$question = $x['en_name'];
			}else{
				$question = "<code>-</code>";
			}

			$x['question'] = $question;

			$x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

			$x['action'] = "<code>-</code>";

        	if ( $x['expire'] <= time() && $x['answered'] == '0' ) {
        		$x['q_status'] = "<span class=\"status-badge-danger\"></span> Javob bermagan";
        		$x['action'] = <<<EOL
                    <button type="button" data-ajax-button="${!${''} = base_url('client/question/status/' . $x['id']) }" data-message="Siz chindan ham ushbu savolni qayta yubormoqchimisiz?" class="btn btn-outline-primary btn-sm waves-light">Qayta yuborish</button>
                EOL;
        	}

        	if ( $x['answered'] > 0 ) {
        		$x['answered'] = date('d.m.Y (H:i)', $x['answered']);
        		$x['q_status'] = "<span class=\"status-badge-success\"></span> Javob bergan";
        	}else{
        		$x['answered'] = "<code>-</code>";
        	}

        	if ( empty( $x['q_status'] ) ) {
        		$x['q_status'] = "<span class=\"status-badge-process\"></span> Jarayonda";
        	}

        	if ( !empty( $x['u_phone'] ) ) {
        		$x['u_phone'] = format_phone( $x['u_phone'] );
        	}else{
        		$x['u_phone'] = "<code>-</code>";
        	}

        	$x['send_date'] = date('d.m.Y (H:i)', $x['send_date']);

        	if ( $x['sended'] == '1' && $x['tg_status'] == '2' ) {
        		$x['send_date'] = '<i class="bx bx-block text-danger"></i> ' . $x['send_date'];
        	}else if ( $x['sended'] == '1' && $x['tg_status'] == '0' ) {
        		$x['send_date'] = '<i class="bx bx-check-double text-success"></i> ' . $x['send_date'];
        	}else if ( $x['sended'] == '0' && $x['tg_status'] == '0' ) {
        		$x['send_date'] = '<i class="bx bx-time-five text-info"></i> ' . $x['send_date'];
        	}

        	$x['send_date'] = "<small>{$x['send_date']}<small>";

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->question->countAll(),
            "recordsFiltered" => $this->question->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

}

/* End of file Question.php */
/* Location: ./application/controllers/client/Question.php */