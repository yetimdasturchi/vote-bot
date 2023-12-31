<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queue extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('polls/Queue_model','queue');

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
			'index_title' => lang('index_queue'),
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
			'content' => 'polls/queue'
		]);
	}

	public function status( $id ){
		$send_date = time() + rand(0, 3600);

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
	        		'removed' => lang('poll_data_successfully_updated')
	        	],
	        	'_callback' => "function(){\$dtables['queue'].ajax.reload(null, false);}"
	    	])
	    );
	}

	public function chance(){
		$this->db->query("UPDATE contest_queue q, ( SELECT @sd := ( UNIX_TIMESTAMP() + FLOOR( 1 + RAND( ) * 172800 ) ) as sd, @sd + 64800 as ex ) sq SET q.expire = sq.ex, q.send_date = sq.sd, q.sended = 0 WHERE q.expire <= UNIX_TIMESTAMP() AND q.answered = 0;");

		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode([
	        	'status' => true,
	        	'messages' => [
	        		'removed' => lang('poll_data_successfully_updated')
	        	],
	        	'_callback' => "function(){\$dtables['queue'].ajax.reload(null, false);}"
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
		
        $memData = $this->queue->getRows( $this->input->post() );
        
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
				$question = "<code>" . lang('polls_undefined') . "</code>";
			}

			$x['question'] = $question;

			$x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

			$x['action'] = "<div class=\"btn-group\" role=\"group\">
                <a class=\"btn btn-warning\" href=\"${!${''} = base_url('users/view/' . $x['u_id']) }\" target=\"_blank\"><i class=\"bx bx-info-circle\"></i></a>
            </div>";

        	if ( $x['expire'] <= time() && $x['answered'] == '0' ) {
        		$x['status'] = "2";
        		$x['action'] = <<<EOL
            <div class="btn-group" role="group">
            <a class="btn btn-warning" href="${!${''} = base_url('users/view/' . $x['u_id']) }" target="_blank"><i class="bx bx-info-circle"></i></a>
            </div>
            <div class="btn-group" role="group">
                <button class="btn btn-success" data-ajax-button="${!${''} = base_url('polls/queue/status/' . $x['id']) }" data-message="${!${''} = lang('polls_do_you_really_chance') }"><i class="mdi mdi-refresh"></i></button>
            </div>
            EOL;
        	}

        	$x['expire'] = date($GLOBALS['system_config']['dateformat'], $x['expire']);
        	$x['send_date'] = date($GLOBALS['system_config']['dateformat'], $x['send_date']);

        	if ( $x['answered'] > 0 ) {
        		$x['answered'] = date($GLOBALS['system_config']['dateformat'], $x['answered']);
        	}else{
        		$x['answered'] = "<code>" . lang('polls_undefined') . "</code>";
        	}

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->queue->countAll(),
            "recordsFiltered" => $this->queue->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

}

/* End of file Queue.php */
/* Location: ./application/controllers/polls/Queue.php */