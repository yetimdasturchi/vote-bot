<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crone extends CI_Controller {

	public function notifications() {
		$this->load->library('Telegram');
		while (1){
			$this->send_notifications();
			sleep(1);
		}
	}
	
	public function send_notifications() {
		$nstatus = file_exists( APPPATH . 'config/notifications.php' ) ? include( APPPATH . 'config/notifications.php' ) : FALSE;
		if ( !$nstatus ) return;

		$query = $this->db->query('SELECT * FROM `notifications` LIMIT 0, 10;');
		if ( $query->num_rows() > 0 ) {
			$query = $query->result_array();

			$ids = array_map(function($x){
				return $x['chat_id'];
			}, $query);

			foreach ($query as $row) {
				$status = $this->send_notification($row);
				if ( !empty( $status['ok'] ) ) {
					$this->db->set('success', 'success+1', FALSE);
				}else{
					$this->db->set('error', 'error+1', FALSE);
				}

				$this->db->set( 'last_send', time() );
				$this->db->where('id', $row['template']);
				$this->db->update('notifications_templates');

				$this->telegram->remove_replyKeyboard();
			}

			$this->db->where_in('chat_id', array_values( $ids ) )->delete('notifications');
		}
	}

	private function send_notificationsa() {

		if( file_exists( APPPATH . 'config/notifications.php' ) ){

		}

		$status = file_exists( APPPATH . 'config/notifications.php' ) ? include( APPPATH . 'config/notifications.php' ) : FALSE;
		if ( !$status ) return;

		$dir = opendir( FCPATH . 'tmp/notifications/' );
		while( false != ( $file = readdir( $dir ) ) ){
			if ( substr( $file,-5 ) == ".json" ){
        		if ( filemtime( FCPATH . 'tmp/notifications/' . $file ) < time() ) {
            		$notification = json_decode( @file_get_contents( FCPATH . 'tmp/notifications/' . $file ), TRUE );
        			if ( !empty( $notification ) ) {
        				$this->send_notification( $notification );
        			}
        			@unlink( FCPATH . 'tmp/notifications/' . $file );
					usleep(50000);
        		}
    		}
		}
	}

	private function send_notification( $data ){
		$this->telegram->set_chatId( $data['chat_id'] );

		if ( !empty( $data['buttons'] ) ) {
			$inline_keyboard = $this->generate_inline_keyboard( json_decode( $data['buttons'], TRUE ) );
			$this->telegram->set_inlineKeyboard( $inline_keyboard );
		}

		if ( !empty( $data['file'] ) ) {
			$data['file'] = json_decode( $data['file'], TRUE );
			$data['type'] = $data['file']['type'];
			$data['file'] = $data['file']['file'];
		}else{
			$data['type'] = 'message'; 
		}

		switch ( $data['type'] ) {
			case 'photo':
				return $this->telegram->send_photo( $data['file'], $data['message'] );
			break;

			case 'video':
				return $this->telegram->send_video( $data['file'], $data['message'] );
			break;

			case 'document':
				return $this->telegram->send_document( $data['file'], $data['message'] );
			break;

			case 'audio':
				return $this->telegram->send_audio( $data['file'], $data['message'] );
			break;

			case 'voice':
				return $this->telegram->send_voice( $data['file'], $data['message'] );
			break;

			case 'animation':
				return $this->telegram->send_animation( $data['file'], $data['message'] );
			break;

			default:
				return $this->telegram->send_message( $data['message'] );
			break;
		}
	}

	private function generate_inline_keyboard( $arr=[] ){
		$keyboard = [];

		foreach ($arr as $value) {
			$tmp = [];

			switch ( $value['type'] ) {
				case 'url':
					$tmp['url'] = $value['value'];
				break;

				case 'callback':
					$tmp['callback_data'] = $value['value'];
				break;

				case 'webapp':
					$tmp['web_app'] = [
						'url' => $value['value']
					];
				break;
			}

			$tmp['text'] = $value['name'];

			$keyboard[] = [ $tmp ];
		}

		return $keyboard;
	}

	public function vote_queue(){
		$this->db->select('contest_queue.*, contest_votes.check_status');
		$this->db->from('contest_queue');
		$this->db->where('contest_queue.answered', '0');
		$this->db->where('contest_queue.sended', '1');
		$this->db->where('contest_queue.expire <', time());
		$this->db->join('contest_votes', 'contest_votes.chat_id = contest_queue.chat_id AND contest_votes.check_status = 0');

		$this->db->group_by('contest_queue.chat_id');

		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$this->db
				->where('chat_id', $row['chat_id'])
				->where('contest', $row['contest'])
				->where_in('check_status', ['0'])
				->update('contest_votes', [
					'check_status' => '2'
				]);
		}
	}

	public function poll_queue(){
		$this->load->model('hook/Poll_model', 'poll');

		$fields = [
			'13' => 'gender',
			'14' => 'gender',
			'15' => 'city',
			'16' => 'city',
			'17' => 'age',
			'18' => 'age',
		];

		$this->db->select('contest_queue.*, users.language, additional_fields.city, additional_fields.gender, additional_fields.age');
		$this->db->from('contest_queue');
		$this->db->where('contest_queue.status', '0');
		$this->db->where('contest_queue.sended', '0');
		$this->db->where('contest_queue.expire >', time());
		$this->db->where('contest_queue.send_date <', time());
		$this->db->join('users', 'users.chat_id = contest_queue.chat_id');
		$this->db->join('additional_fields', 'additional_fields.user_id = users.id', 'left');

        $query = $this->db->get();
		if ( $query->num_rows() > 0 ) {
			$query = $query->result_array();
			$x = 0;
			foreach ($query as $row) {
				foreach (['uzbek', 'uzbek_cyr', 'russian', 'english'] as $lang) {
					if ( !empty( $row['poll_'.$lang] ) ) {
						$language = $lang;
						$poll = $row['poll_'.$lang];
						break;
					}
				}

				if ( array_key_exists($poll, $fields) ) {
					if ( !empty( $row[ $fields[ $poll ] ] ) ) {
						if ( $fields[ $poll ] == 'age' ) {
							if ( isValidYear( $row[ $fields[ $poll ] ] ) ) {
								$this->update_poll_sended( $row['id'], '0', time(), '1');
								$this->set_poll_status( $row['chat_id'], $row['contest'], $row['polls_count']);
								continue;
							}else{
								$this->db->delete('poll_type', [
									'chat_id' => $row['chat_id'],
									'question_id' => $poll,
								]);
							}
						}else{
							$this->update_poll_sended( $row['id'], '0', time(), '1');
							$this->set_poll_status( $row['chat_id'], $row['contest'], $row['polls_count']);
							continue;
						}
					}

					if ( $fields[ $poll ] != 'age' ) {
						$this->db->delete('poll', [
							'chat_id' => $row['chat_id'],
							'question_id' => $poll,
						]);
					}
				}

				if ( !isset( $language ) && !isset( $poll ) ) {
					$this->update_poll_sended( $row['id'] );
					continue;
				}

				$this->unload_language();
				$this->lang->load('hook', $language);
				$res = $this->poll->send_question($poll, $row['chat_id'], FALSE, $row['id'], $row['expire']);
				$st = '0';
				if ( !empty( $res['description'] ) ) {
					if ( preg_match('/bot\s+was\s+blocked\s+by\s+the\s+user/', $res['description']) ) {
						$st = '2';
					}
				}
				$this->update_poll_sended( $row['id'], $st);

				$x++;

				if ( $x > 15 ) {
					$x=0;
					sleep(1);
				}
			}
		}
	}

	public function set_poll_status( $chat_id, $contest, $polls_count ){
		$status = $this->db->get_where('contest_queue', [
			'contest' => $contest,
			'chat_id' => $chat_id
		]);

		if ( $status->num_rows() > 0 ) {
			$answered = 0;
			$uanswered = 0;
			$all = 0;
			foreach ($status->result_array() as $row) {
				if ( $row['status'] == '1' ) {
					$answered++;
					$all++;
				}
				if ( $row['status'] == '0' && $row['expire'] <= time() ) {
					$uanswered++;
					$all++;
				}
				
			}

			if ( ( $answered + $uanswered ) >= intval( $polls_count ) ) {
				//if ( $answered > $uanswered ) {
				if ( $answered >= intval( $polls_count ) ) {
					$st = '1';
				}else if ( $uanswered > $answered ) {
					$st = '2';
				}else{
					$st = '2';
				}

				$this->db
					->where('chat_id', $chat_id)
					->where('contest', $contest)
					->where_in('check_status', ['0', '2'])
					->update('contest_votes', [
						'check_status' => $st
					]);
			}
			
		}
	}

	public function update_poll_sended( $id, $status, $answered = '', $astatus = '' ){
		$new_data = [
			'sended' => '1',
			'tg_status' => $status
		];

		if ( $answered != '' ) {
			$new_data['answered'] = $answered;
		}

		if ( $astatus != '' ) {
			$new_data['status'] = $astatus;
		}

		$this->db->update('contest_queue', $new_data, [
			'id' => $id
		]);
	}

	public function unload_language(){
		if(isset($this->lang->is_loaded)){
    		for($i=0; $i<=sizeof($this->lang->is_loaded); $i++){
        		unset($this->lang->is_loaded[$i]);
    		}
		}
	}

}

/* End of file Crone.php */
/* Location: ./application/controllers/Crone.php */