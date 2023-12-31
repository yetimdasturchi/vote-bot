<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook_model extends CI_Model {
	public $module_name;

	public function load( $module_name ){
		
	}

	public function run(){
		
	}

	public function process(){
		$query_data = $this->user->get_data('active_function_args');
		parse_str($query_data, $query);

		if ( !empty( $query['ques'] ) && !empty( $query['qu'] ) && !empty( $query['que'] ) ) {
			$question = $this->db->get_where('poll_questions', [
				'id' => $query['ques']
			]);

			if ( $question->num_rows() > 0 ) {
				$poll_type = $this->db->get_where('poll_type', [
					'chat_id' => $this->telegram->get_chatId(),
					'question_id' => $query['ques']
				]);

				if ( $poll_type->num_rows() > 0 ) {
					$poll_type = $poll_type->row_array();
					$this->user->set_data([
						'active_function',
						'active_function_args'
					], [
						'',
						''
					]);
					$this->telegram->set_replyKeyboard([], '', TRUE)->send_message( str_replace(['{answer}'], [ $poll_type['answer'] ], lang('allready_answered') ) );
					exit();
				}

				$question = $question->row_array();

				$contest_queue = $this->db->select('*')->from('contest_queue')->where('id', $query['qu'])->where('expire >', time() )->where('status', '0')->get();
		
				if ( $contest_queue->num_rows() > 0 ) {
					$contest_queue = $contest_queue->row_array();

					if ( $question['additional_field'] == 'age' ) {
						if( !isValidYear( $this->user->command_text ) ) {
							$this->telegram->send_message( lang('year_is_not_valid') );
							exit();
						}
					}

					if ( !empty( $question['additional_field'] ) ) {
						$this->user->set_additional_data( $question['additional_field'], $this->user->command_text );
					}

					$this->db->update('contest_queue', [
						'answered' => time(),
						'status' => '1'
					],[
						'id' => $query['qu']
					]);

					$status = $this->db->get_where('contest_queue', [
						'contest' => $contest_queue['contest'],
						'chat_id' => $this->user->chat_id
					]);

					if ( $status->num_rows() > 0 ) {
						$answered = 0;
						$uanswered = 0;
						foreach ($status->result_array() as $row) {
							if ( $row['status'] == '1' ) $answered++;
							if ( $row['status'] == '0' && $row['expire'] <= time() ) $uanswered++;
							
						}

						if ( ( $answered + $uanswered ) >= intval( $contest_queue['polls_count'] ) ) {
							//if ( $answered > $uanswered ) {
							if ( $answered >= intval( $contest_queue['polls_count'] ) ) {
								$st = '1';
							}else if ( $uanswered > $answered ) {
								$st = '2';
							}else{
								$st = '2';
							}
							
							$this->db
								->where('chat_id', $this->user->chat_id)
								->where('contest', $contest_queue['contest'])
								->where_in('check_status', ['0', '2'])
								->update('contest_votes', [
									'check_status' => $st
								]);
						}
						
					}

					$this->db->insert('poll_type', [
						'question_id' => $query['ques'],
						'answer' => $this->user->command_text,
						'chat_id' => $this->user->chat_id,
						'date' => time()
					]);

					$this->user->set_data([
						'active_function',
						'active_function_args'
					], [
						'',
						''
					]);
					$this->bot->remove_keyboard('answer_type');
					$this->user->active_function = '';
					//$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE );

					if ( isset( $st ) ) {
						if ( $st == '1' ) {
							$this->bot->messageText( '/polls_success', $this->user->chat_id, 'command_set', FALSE );	
						}else if ( $st == '2' ) {
							$this->bot->messageText( '/polls_error', $this->user->chat_id, 'command_set', FALSE );	
						}
					}else{
						$this->bot->messageText( '/inline_answered', $this->user->chat_id, 'command_set', FALSE );	
					}

					exit();
				}else{
					$this->back('poll_expired');
				}
			}else{
				$this->back('poll_expired');
			}
		}else if ( !empty( $query['ques'] ) ) {
			$question = $this->db->get_where('poll_questions', [
				'id' => $query['ques']
			]);

			if ( $question->num_rows() > 0 ) {
				$poll_type = $this->db->get_where('poll_type', [
					'chat_id' => $this->telegram->get_chatId(),
					'question_id' => $query['ques']
				]);

				if ( $poll_type->num_rows() > 0 ) {
					$poll_type = $poll_type->row_array();
					$this->user->set_data([
						'active_function',
						'active_function_args'
					], [
						'',
						''
					]);
					$this->telegram->set_replyKeyboard([], '', TRUE)->send_message( str_replace(['{answer}'], [ $poll_type['answer'] ], lang('allready_answered') ) );
					exit();
				}

				$question = $question->row_array();

				if ( $question['additional_field'] == 'age' ) {
					if( !isValidYear( $this->user->command_text ) ) {
						$this->telegram->send_message( lang('year_is_not_valid') );
						exit();
						}
				}

				if ( !empty( $question['additional_field'] ) ) {
					$this->user->set_additional_data( $question['additional_field'], $this->user->command_text );
				}

				$this->db->insert('poll_type', [
					'question_id' => $query['ques'],
					'answer' => $this->user->command_text,
					'chat_id' => $this->user->chat_id,
					'date' => time()
				]);

				$this->user->set_data([
					'active_function',
					'active_function_args'
				], [
					'',
					''
				]);
				$this->bot->remove_keyboard('answer_type');
				$this->user->active_function = '';
				exit();
			}else{
				$this->back('poll_expired');
			}			
		}else{
			$this->back('poll_expired');
		}

		return FALSE;
	}

	public function back( $message = 'continue' ){

		$this->telegram->set_replyKeyboard([], '', TRUE)->send_message( lang( $message ) );

		$this->user->set_data([
			'active_function',
			'active_function_args'
		], [
			'',
			''
		]);
		$this->user->active_function = '';
		$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE );
		exit();
	}
}

/* End of file Hook_model.php */
/* Location: ./application/modules/answer_type/models/Hook_model.php */