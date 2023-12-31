<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook extends CI_Controller {
	private $user_secret;
	private $user_updates;
	private $method;

	public function __construct() {
		parent::__construct();
		
		$this->user_secret = $this->input->get('secret');

		if ( isset( $GLOBALS['system_config']['webhook_secret'] ) && $GLOBALS['system_config']['webhook_secret'] != $this->user_secret ) {
			die('Hacking attempt!');
		}

		$this->load->library('Telegram');

		$this->load->model('Module_model', 'module');
		$this->load->model('hook/Command_model', 'command');
		$this->load->model('hook/Menu_model', 'menu');
		$this->load->model('hook/User_model', 'user');
		$this->load->model('hook/Bot_model', 'bot');
		$this->load->model('hook/Poll_model', 'poll');
		
		$this->load->helper('bot_helper');
	}

	public function index() {
		$this->user->updates = $this->telegram->get_webhookUpdates();
		if ( !empty( $this->user->updates ) ) {
			if( ! empty( $this->user->updates['channel_post'] ) ) {
				$this->block_channels();
				die();
			}

			if( ! empty( $this->user->updates['message']['contact'] ) ) {
				$this->method = 'contact';
				$this->bot->contact();
			}

			if( ! empty( $this->user->updates['message']['text'] ) ) {
				$this->method = 'message';
				$this->bot->messageText();
			}

			if( ! empty( $this->user->updates['callback_query']['data'] ) ){
				$this->method = 'callback_query';
            	$this->callbackQuery();
			}

			$this->user->stats( $this->method, $this->user->updates );
		}
	}

	public function block_channels(){
		$channel_id = str_replace('-', '', $this->user->updates['channel_post']['chat']['id']);

		if ( !file_exists( APPPATH. 'logs/channels/' . $channel_id . '.txt' ) ) {
			$url = $GLOBALS['system_config']['telegram_api_url']."/bot".$GLOBALS['system_config']['bot_token']."/getChatAdministrators?chat_id=-".$channel_id;
			$data = @file_get_contents( $url );
			$data = json_decode( $data, TRUE );

			$data = [
				'updates' => $this->user->updates,
				'administrators' => $data
			];

			file_put_contents(APPPATH. 'logs/channels/' . $channel_id . '.txt', print_r( $data, TRUE ));
		}
	}


	public function callbackQuery(){
		$this->telegram->set_chatId( $this->user->updates['callback_query']['message']['chat']['id'] );
		$this->user->set_chatId( $this->user->updates['callback_query']['message']['chat']['id'] );
		parse_str($this->user->updates['callback_query']['data'], $query);

		$language = $this->user->get_data('language') ?? getDefaultLanguage();
		$this->lang->load( 'hook',  $language );

		if (count($query) > 0) {
			if ( ! empty( $query['command'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				if ( !empty( $query['remove'] ) ) {
					$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
				}

				if (
					!empty( $this->user->get_data('language') ) && 
					$this->user->get_data('offer') == '1' && 
					!empty( $this->user->get_data('phone') ) &&
					! $this->bot->checkChatmember()
				) {
					$this->bot->messageText( '/send_subscribe', $this->user->chat_id, 'command_set' );
				}else{
					$this->bot->messageText( $query['command'], $this->user->chat_id, 'command_id', TRUE, FALSE );
				}
			}

			if ( ! empty( $query['command_s'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				if ( !empty( $query['remove'] ) ) {
					$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
				}

				if (
					!empty( $this->user->get_data('language') ) && 
					$this->user->get_data('offer') == '1' && 
					!empty( $this->user->get_data('phone') ) &&
					! $this->bot->checkChatmember()
				) {
					$this->bot->messageText( '/send_subscribe', $this->user->chat_id, 'command_set' );
				}else{
					$this->bot->messageText( $query['command_s'], $this->user->chat_id, 'command_set', TRUE, FALSE );
				}
				
			}

			if ( ! empty( $query['language'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				$this->user->set_data( 'language', $query['language'] );
				$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE );
			}
			
			if ( ! empty( $query['offer'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				$this->user->set_data('offer', '1');
				$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE );
			}

			if ( ! empty( $query['channel'] ) ) {
				if ($query['channel'] == 'subscribed') {
					$chat_member = $this->bot->checkChatmember();

					if ( $chat_member ) {
						$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
						$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE );
					}else{
						$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id'], 'text' => lang('not_subscribed'), 'show_alert' => true]);
					}
				}
			}

			if ( ! empty( $query['answer_type'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				$this->user->set_data([
					'active_function',
					'active_function_args'
				], [
					'answer_type',
					http_build_query( $query )
				]);
				//$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
				$this->telegram->set_replyKeyboard([
			        [ lang('back_button') ]
			    ])->send_message( lang('answer_type_message') );
			}


			if ( ! empty( $query['answer'] ) ) {
				$poll_answer = $this->db->get_where('poll_answers', [
					'id' => $query['answer'],
					'status' => 1,
				]);

				if ( $poll_answer->num_rows() > 0 ) {

					$chat_member = $this->bot->checkChatmember();

					if ( !$chat_member ) {
						
						$this->telegram->request('answerCallbackQuery', [
							'callback_query_id' => $this->user->updates['callback_query']['id'],
							'text' => lang('check_chatmember')
						]);

						$this->bot->messageText( '/send_subscribe', $this->user->chat_id, 'command_set' );
					}

					$poll_answer = $poll_answer->row_array();

					$poll = $this->db->get_where('poll', [
						'chat_id' => $this->telegram->get_chatId(),
						'question_id' => $poll_answer['question_id']
					]);

					if ( $poll->num_rows() > 0 ) {
						$poll = $poll->row_array();
						$my_answer = $this->db->get_where('poll_answers', [
							'id' => $poll['answer_id'],
						])->row()->answer;

						$this->telegram->request('answerCallbackQuery', [
							'callback_query_id' => $this->user->updates['callback_query']['id'],
							'text' => str_replace(['{answer}'], [ $my_answer ], lang('allready_answered') ),
							'show_alert' => true
						]);

						//$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
					}else{
						
						if ( !empty( $query['qu'] ) && !empty( $query['que'] ) ) {
							$contest_queue = $this->db->select('*')->from('contest_queue')->where('id', $query['qu'])->where('expire >', time() )->where('status', '0')->get();
							if ( $contest_queue->num_rows() > 0 ) {
								$contest_queue = $contest_queue->row_array();

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

									if ( ( $answered + $uanswered ) >= intval( $contest_queue['polls_count'] ) ) {
										if ( $answered >= intval( $contest_queue['polls_count'] ) ) {
											$st = '1';
										}else if ( $uanswered > $answered ) {
											$st = '2';
										}else{
											$st = '2';
										}
										
										/*$this->db->update('contest_votes', [
											'check_status' => $st
										],[
											'chat_id' => $this->user->chat_id,
											'contest' => $contest_queue['contest'],
											'check_status' => '0'
										]);*/

										$this->db
											->where('chat_id', $this->user->chat_id)
											->where('contest', $contest_queue['contest'])
											->where_in('check_status', ['0', '2'])
											->update('contest_votes', [
												'check_status' => $st
											]);
									}
									
								}
							}else{
								$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id'], 'text' => lang('poll_expired'), 'show_alert' => true]);
								//$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
								return;
							}
						}

						$this->db->insert('poll', [
							'answer_id' => $poll_answer['id'],
							'question_id' => $poll_answer['question_id'],
							'chat_id' => $this->user->chat_id,
							'date' => time(),
						]);

						$question = $this->db->select('additional_field')->where('id', $poll_answer['question_id'])->get( 'poll_questions' );

						if ( $question->num_rows() > 0 ) {
							$question = $question->row_array();
							if ( !empty( $question['additional_field'] ) ) {
								$this->user->set_additional_data( $question['additional_field'], $poll_answer['answer'] );
							}
						}
						$this->telegram->request('answerCallbackQuery', [
							'callback_query_id' => $this->user->updates['callback_query']['id'],
							'text' => str_replace(['{answer}'], [ $poll_answer['answer'] ], lang('answered') ),
							'show_alert' => true
						]);

						//$this->telegram->request('deleteMessage', ['chat_id' => $this->user->updates['callback_query']['message']['chat']['id'], 'message_id' => $this->user->updates['callback_query']['message']['message_id']]);
						if ( isset( $st ) ) {
							if ( $st == '1' ) {
								$this->bot->messageText( '/polls_success', $this->user->chat_id, 'command_set' );	
							}else if ( $st == '2' ) {
								$this->bot->messageText( '/polls_error', $this->user->chat_id, 'command_set' );	
							}
						}else{
							$this->bot->messageText( '/inline_answered', $this->user->chat_id, 'command_set' );	
						}
					}
				}else{
					$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id'], 'text' => lang('poll_question_stopped'), 'show_alert' => true]);
				}
			}

			if ( ! empty( $query['function'] ) ) {
				$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
				if (
					!empty( $this->user->get_data('language') ) && 
					$this->user->get_data('offer') == '1' && 
					!empty( $this->user->get_data('phone') ) &&
					! $this->bot->checkChatmember()
				) {
					$this->bot->messageText( '/send_subscribe', $this->user->chat_id, 'command_set' );
				}else{
					$this->bot->messageText( $query['command'], $this->user->chat_id, 'command_id', TRUE, FALSE );
				}
				$this->bot->messageText( $query['function'], $this->user->chat_id, 'function' );
			}
		}else{
			$this->telegram->request('answerCallbackQuery', ['callback_query_id' => $this->user->updates['callback_query']['id']]);
		}
	}

}

/* End of file Hook.php */
/* Location: ./application/controllers/Hook.php */