<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contest extends CI_Controller {

	private $contest_id;
	private $nomination_id;
	private $nomination_query;
	private $contest_query;
	private $votes_id;
	private $hash;

	public function __construct() {
		parent::__construct();

		$this->load->library('Encryptor');
		$this->load->helper('tasix');
		$this->load->model('webview/Contest_model', 'contest');
	}

	public function _remap($method){

		$ip = $this->input->ip_address();
		
		$hash_str = $this->input->get('hash');
		$this->hash = $this->encryptor->xorDecryptArray( $hash_str );

		if ( empty( $this->hash ) ) return $this->close();
		
		$chat_id = $this->input->get('chat_id');

		if ( $chat_id != $this->hash['chat_id'] ) return $this->close();
		
		$user = $this->db->get_where('users', [
			'chat_id' => $chat_id
		]);

		if ( $user->num_rows() > 0 ) {
			$user = $user->row_array();
			if ( empty( $user['phone'] ) ) {
				$this->load->library('Telegram');
				$this->load->model('hook/User_model', 'user');
				$this->load->model('hook/Bot_model', 'bot');
				$this->load->model('hook/Command_model', 'command');
				$this->load->helper('bot_helper');
				$this->telegram->set_chatId( $chat_id );
				$this->user->set_chatId( $chat_id );
				$this->bot->messageText( '/phone', $chat_id );
				return $this->close();
			}
		}

		if ( !in_array(  $chat_id, ['74794646'] ) ) {
			//if ( !check_tasix( $ip ) ) return $this->close();
		}

		if ( !$this->contest->check_hash( $chat_id, $hash_str ) ) return $this->close();

		if ( get_cookie('language') ) {
			$this->config->set_item('language',  get_cookie('language') );
		}
		
		if ( !empty( $this->hash['language'] ) && in_array( $this->hash['language'], array_keys( getLanguages(TRUE) ) ) ) {
			$this->config->set_item('language',  $this->hash['language'] );
			set_cookie( 'language', $this->hash['language'], strtotime("+1 year") );
		}

		$this->lang->load( 'contest');

		$this->index();
	}

	public function index() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$action = $this->input->post('action');
			switch ($action) {
				case 'vote':
					return $this->vote();
				break;
				
				default:
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(400)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'messages' => [
				        		lang('contest_undefined_action')
				        	]
				    	])
				    );
				break;
			}
		}

		$this->contest_id = $this->uri->segment(2);

		if ( empty( $this->contest_id ) ) {
			return $this->close();
		}

		$this->contest_query = $this->contest->get_contest( $this->contest_id );

		if ( empty( $this->contest_query ) ) {
			return $this->close();
		}

		$this->votes_id = $this->contest->getVotesId( $this->contest_id, $this->hash['chat_id'] );

		$this->nomination_id = $this->uri->segment(3);
		
		if ( empty( $this->nomination_id ) ) {
			return $this->nominations();
		}

		$this->member_id = $this->uri->segment(4);
		
		if ( empty( $this->member_id ) ) {
			return $this->members();
		}

		return $this->detail();
		
	}

	public function detail() {
		$member = $this->contest->getMemberById( $this->member_id );

		if ( empty( $member ) ) {
			return $this->close();
		}

		$this->load->view('webview/detail', [
			'member' => $member,
			'contest_query' => $this->contest_query,
			'nomination_id' => $this->nomination_id,
			'contest_id' => $this->contest_id,
			'votes_id' => $this->votes_id
		]);
	}

	public function members(){
		$members = $this->contest->getMemberByNomination( $this->contest_id, $this->nomination_id );

		if ( empty( $members ) ) {
			return $this->close();
		}

		$this->load->view('webview/members', [
			'members' => $members,
			'nomination_id' => $this->nomination_id,
			'votes_id' => $this->votes_id
		]);
	}

	private function nominations(){
		$nominations = $this->contest->getNominationsByContest( $this->contest_id );

		if ( empty( $nominations ) ) {
			return $this->close();
		}

		$this->load->view('webview/nominations', [
			'nominations' => $nominations,
			'contest_id' => $this->contest_id,
			'votes_id' => $this->votes_id
		]);
	}

	private function vote(){
		if ( empty( $this->contest_query['expire'] ) || $this->contest_query['expire'] > time() ) {
			$contest_id = $this->input->post('contest');
			$nomination_id = $this->input->post('nomination');
			$member_id = $this->input->post('member');
			$chat_id = $this->input->post('chat');

			if ( empty( $contest_id ) || empty( $nomination_id ) || empty( $member_id ) || empty( $chat_id ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('contest_required_fields_notfilled')
			        	]
			    	])
		    	);
			}

			if ( $this->hash['chat_id'] != $chat_id ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('contest_no_user_found')
			        	]
			    	])
		    	);
			}

			if ( $this->contest->checkVotedToMember( $member_id, $nomination_id, $chat_id ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'hide_button' => true,
			        	'messages' => [
			        		lang('contest_already_voted')
			        	]
			    	])
		    	);
			}

			if ( $this->contest->checkVotedToNomination( $nomination_id, $chat_id ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'hide_button' => true,
			        	'messages' => [
			        		lang('contest_nomination_already_voted')
			        	]
			    	])
		    	);
			}

			$nomination = $this->contest->getNominationById( $nomination_id );

			if ( empty( $nomination ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('contest_no_nomination_found')
			        	]
			    	])
		    	);
			}

			$member = $this->contest->getMemberById( $member_id );

			if ( empty( $member ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('contest_no_member_found')
			        	]
			    	])
		    	);
			}

			if ( !$this->contest->checkUserByChatId( $chat_id ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		lang('contest_no_user_found')
			        	]
			    	])
		    	);
			}

			$check_status = $this->contest->vote( $contest_id, $nomination_id, $member_id, $chat_id );

			$res = [
		        'status' => true
		    ];

		    if ( $check_status == '1' ) {
		    	$res['_callback'] = 'function(){$(".vote-success-message").text("'.lang('contest_voted_success').'")}';
		    }

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode($res));

		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => [
		        		lang('contest_expired')
		        	]
		    	])
		    );
		}
	}

	private function close(){
		return $this->load->view('webview/close');
	}

}

/* End of file Webview.php */
/* Location: ./application/controllers/Webview.php */