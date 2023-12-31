<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poll_model extends CI_Model {
	public function __construct() {
    	parent::__construct();

		$this->load->library('Telegram');
	}

	public function send_question( $question_id, $chat_id = 0, $url = FALSE, $queue = '', $queue_expire = 0 ){
		if ($chat_id > 0) {
			$this->telegram->set_chatId( $chat_id );
		}

		$question = $this->db->get_where('poll_questions', [
			'id' => $question_id,
			'status' => '1',
		]);
		if ( $question->num_rows() > 0 ) {
			$question = $question->row_array();
			
			$question_answers = $this->db->get_where('poll_answers', [
				'question_id' => $question_id,
				'status' => '1',
			]);

			if ( $question['type'] == '0' &&  $question_answers->num_rows() > 0 ) {
				$question_answers = $question_answers->result_array();

				$answers_button = [];

				foreach ($question_answers as $answer) {
					$count_a = $this->db->where('answer_id', $answer['id'])->count_all_results( 'poll');

					if ( $url ) {
						if ( is_bool( $url ) ) {
							$bot_username = $GLOBALS['system_config']['bot_username'];
							$url = "http://t.me/{$bot_username}?start=que-{$question_id}";
						}
						$answers_button[] = [
		                	[
		                    	"text" =>  str_replace(
		                    		['{answer}', '{answers_count}'],
		                    		[$answer['answer'], $count_a],
		                    		lang('answer_button_url')
		                    	),
		                    	"url" => $url
		                	]
		            	];
					}else{
						
						$callback_data = [
							'answer' => $answer['id']
						];

						if ( !empty( $queue ) ) {
							$callback_data['qu'] = $queue;
						}

						if ( !empty( $queue_expire ) ) {
							$callback_data['que'] = $queue_expire;
						}

						$answers_button[] = [
		                	[
		                    	"text" =>  str_replace(
		                    		['{answer}', '{answers_count}'],
		                    		[$answer['answer'], $count_a],
		                    		lang('answer_button_url')
		                    	),
		                    	"callback_data" => http_build_query( $callback_data )
		                	]
		            	];
					}
				}

				$this->telegram->set_inlineKeyboard( $answers_button );

				$message = lang('inline_question');

				preg_match_all('/{(.*)}/', $message, $message_matches, PREG_SET_ORDER);

				if ( !empty( $message_matches ) ) {
					foreach ($message_matches as $match) {
						switch ( $match[1] ) {
							case 'question':
								$message = str_replace( $match[0], $question['question'], $message);
							break;

							case 'expire':
								$message = str_replace( $match[0], date("Y-m-d | H:i", $question['expire']), $message);
							break;

							case 'url':
								$url = lang('question_url');
								$bot_username = $GLOBALS['system_config']['bot_username'];
								$url = "<a href=\"http://t.me/{$bot_username}?start=que-{$question_id}\">{$url}</a>";
								$message = str_replace( $match[0], $url, $message);
							break;

							case 'answer_rating':
								$message = str_replace( $match[0], $this->answer($question_id), $message);
							break;
							
							default:
								$message = str_replace( $match[0], '', $message);
							break;
						}
					}
				}

				if ( empty( $message ) ) $message = '';

				if ( !empty( $question['file'] ) ) {
					$file = json_decode( $question['file'], TRUE );

					switch ( $file['type'] ) {
						case 'photo':
							return $this->telegram->send_photo( $file['file'], $message );
						break;

						case 'video':
							return $this->telegram->send_video( $file['file'], $message );
						break;

						case 'audio':
							return $this->telegram->send_audio( $file['file'], $message );
						break;

						case 'document':
							return $this->telegram->send_document( $file['file'], $message );
						break;
					}
				}else{
					return $this->telegram->send_message( $message );
				}
			}else if ( $question['type'] == '1' && !$url) {
				$answers_button = [];

				$callback_data = [
					'answer_type' => 'true',
					'ques' => $question['id']
				];

				if ( !empty( $queue ) ) {
					$callback_data['qu'] = $queue;
				}

				if ( !empty( $queue_expire ) ) {
					$callback_data['que'] = $queue_expire;
				}

				$answers_button[] = [
                	[
                    	"text" =>  lang('answer_type'),
                    	"callback_data" => http_build_query( $callback_data )
                	]
            	];

            	$this->telegram->set_inlineKeyboard( $answers_button );

				$message = lang('inline_question');

				preg_match_all('/{(.*)}/', $message, $message_matches, PREG_SET_ORDER);

				if ( !empty( $message_matches ) ) {
					foreach ($message_matches as $match) {
						switch ( $match[1] ) {
							case 'question':
								$message = str_replace( $match[0], $question['question'], $message);
							break;

							case 'expire':
								$message = str_replace( $match[0], date("Y-m-d | H:i", $question['expire']), $message);
							break;

							case 'url':
								$url = lang('question_url');
								$bot_username = $GLOBALS['system_config']['bot_username'];
								$url = "<a href=\"http://t.me/{$bot_username}?start=que-{$question_id}\">{$url}</a>";
								$message = str_replace( $match[0], $url, $message);
							break;

							case 'answer_rating':
								$message = str_replace( $match[0], $this->answer($question_id), $message);
							break;
							
							default:
								$message = str_replace( $match[0], '', $message);
							break;
						}
					}
				}

				if ( empty( $message ) ) $message = '';

				if ( !empty( $question['file'] ) ) {
					$file = json_decode( $question['file'], TRUE );

					switch ( $file['type'] ) {
						case 'photo':
							return $this->telegram->send_photo( $file['file'], $message );
						break;

						case 'video':
							return $this->telegram->send_video( $file['file'], $message );
						break;

						case 'audio':
							return $this->telegram->send_audio( $file['file'], $message );
						break;

						case 'document':
							return $this->telegram->send_document( $file['file'], $message );
						break;
					}
				}else{
					return $this->telegram->send_message( $message );
				}
			}
		}

		return FALSE;
	}

	public function update_question( $question_id, $chat_id, $message_id, $url = FALSE){
		if ($chat_id > 0) {
			$this->telegram->set_chatId( $chat_id );
		}

		

		$question = $this->db->get_where('poll_questions', [
			'id' => $question_id,
			'status' => '1',
		]);
		if ( $question->num_rows() > 0 ) {
			$question = $question->row_array();
			
			$question_answers = $this->db->get_where('poll_answers', [
				'question_id' => $question_id,
				'status' => '1',
			]);

			if ( $question_answers->num_rows() > 0 ) {
				$question_answers = $question_answers->result_array();

				$answers_button = [];

				foreach ($question_answers as $answer) {
					$count_a = $this->db->where('answer_id', $answer['id'])->count_all_results( 'poll');

					if ( $url ) {
						$answers_button[] = [
		                	[
		                    	"text" =>  str_replace(
		                    		['{answer}', '{answers_count}'],
		                    		[$answer['answer'], $count_a],
		                    		lang('answer_button_url')
		                    	),
		                    	"url" => $url
		                	]
		            	];
					}else{
						$answers_button[] = [
		                	[
		                    	"text" =>  str_replace(
		                    		['{answer}', '{answers_count}'],
		                    		[$answer['answer'], $count_a],
		                    		lang('answer_button_url')
		                    	),
		                    	"callback_data" => "answer={$answer['id']}"
		                	]
		            	];
					}
				}

				//$this->telegram->set_inlineKeyboard( $answers_button );

				$message = lang('inline_question');

				preg_match_all('/{(.*)}/', $message, $message_matches, PREG_SET_ORDER);

				if ( !empty( $message_matches ) ) {
					foreach ($message_matches as $match) {
						switch ( $match[1] ) {
							case 'question':
								$message = str_replace( $match[0], $question['question'], $message);
							break;

							case 'expire':
								$message = str_replace( $match[0], date("Y-m-d | H:i", $question['expire']), $message);
							break;

							case 'url':
								$url = lang('question_url');
								$bot_username = $GLOBALS['system_config']['bot_username'];
								$url = "<a href=\"http://t.me/{$bot_username}?start=que-{$question_id}\">{$url}</a>";
								$message = str_replace( $match[0], $url, $message);
							break;

							case 'answer_rating':
								$message = str_replace( $match[0], $this->answer($question_id), $message);
							break;
							
							default:
								$message = str_replace( $match[0], '', $message);
							break;
						}
					}
				}

				if ( in_array( $question['file_type'] , ['photo', 'video', 'audio', 'document'] ) ) {
					$req = $this->telegram->request('editMessageCaption', [
                    	'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'reply_markup' => [
                        	'inline_keyboard' => $answers_button
                        ],
                        'caption' => $message,
                        'parse_mode' => 'html',
                        'disable_web_page_preview' => true
                    ]);
				}else{
					$req = $this->telegram->request('editMessageText', [
                    	'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'reply_markup' => [
                        	'inline_keyboard' => $answers_button
                        ],
                        'text' => $message,
                        'parse_mode' => 'html',
                        'disable_web_page_preview' => true
                    ]);
				}

				if ( $req['ok'] == FALSE ) {
					if ( $req['description'] == "Bad Request: MESSAGE_ID_INVALID" || $req['description'] == "Bad Request: message to edit not found") {
						$this->db->delete('channel_messages', [
							'message_id' => $message_id
						]);
					}
				}
			}
		}else{
			$this->telegram->send_chatAction('typing')->send_message( lang('poll_question_stopped') );
		}
	}

	public function answer( $question_id ) {
		$result = "";

		$question = $this->db->get_where('poll_questions', [
			'id' => $question_id,
		]);

		if ( $question->num_rows() > 0 ) {
			$question = $question->row_array();
			
			$question_answers = $this->db->get_where('poll_answers', [
				'question_id' => $question_id
			]);

			if ( $question_answers->num_rows() > 0 ) {
				$question_answers = $question_answers->result_array();

				$total_rating = $this->db->where('question_id', $question_id)->count_all_results('poll');

				foreach($question_answers as $answer) {
					$answers_count = $this->db->where('question_id', $question_id)
						->where('answer_id', $answer['id'])
						->count_all_results('poll');
					
					$percentage = 0;
		            
		            if(!empty($total_rating)) {
		                $percentage = ( $answers_count / $total_rating ) * 100;
		                if(is_float($percentage)) {
		                    $percentage = number_format($percentage,2);
		                }
		            }

		            $result .= str_replace(
		                ['{answer}', '{persentage}', '{count}'],
		                [$answer['answer'], $percentage, $answers_count],
		            	lang('answer_rating_single')
					);
				}
			}
		}

		return $result;
	}
}

/* End of file Poll_model.php */
/* Location: ./application/models/hook/Poll_model.php */