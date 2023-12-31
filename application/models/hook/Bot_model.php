<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot_model extends CI_Model {

	public $not_subscribed_channels = [];

	public function messageText( $command_text='', $chat_id = '', $by_key='command_set', $function_active = TRUE, $offer = TRUE, $phone = TRUE ){
		if ( $chat_id == '' ) {
			$this->telegram->set_chatId( $this->user->updates['message']['chat']['id'] );
			$this->user->set_chatId( $this->user->updates['message']['chat']['id'] );
		}

		/*if ( !( $chat_id == '441307831' || $this->user->updates['message']['chat']['id'] == '441307831' ) ) {
			//$this->telegram->send_message('Texnik ishlar ketmoqda. Bot tez orada o\'z faoliyatini tiklaydi');
			exit();
		}*/
		
		$language = $this->user->get_data('language') ?? getDefaultLanguage();
		
		$this->lang->load( 'hook',  $language );

		if ( $command_text != '' ) {
			$this->user->command_text = $command_text;
		}else{
			$this->user->command_text = $this->user->updates['message']['text'];

			if (preg_match('/\/start r-(\d+)/', $this->user->command_text, $refmatches)) {
				if ( $refmatches[1] != $this->user->chat_id && !$this->user->check_ref() ) {
					$this->user->add_ref( $refmatches[1] );
				}

				$this->user->command_text = '/main';
			}

			if ( $this->user->command_text == '/start' ) {
				$this->user->command_text = '/main';
			}

			//No not use is in production
			/*if ( $this->user->command_text == '/remove_cacheasdasdasdasasdas' ) {
				$this->db->update('users', [
					'phone' => '',
					'active_function' => '',
					'hash' => '',
					'offer' => '0',
					'language' => '0',
				]);

				$this->db->delete('contest_votes');

				clear_cache([
                	'command',
                	'commands',
                	'user',
            	]);

            	$this->telegram->send_message('ok');
            	exit();
			}*/
		}

		$this->user->active_function = $function_active ? $this->user->get_data('active_function') : FALSE;
		
		if ( $this->user->command_text == lang('back_button') ) {
			if ( $this->user->active_function ) {
				$this->call_user_function( 'back' );
			}else{
				$this->menu->back();
			}
		}

		if ( $this->user->active_function  ) {
			$process_status = $this->call_user_function( 'process' );
			if ( $process_status ) {
				return $process_status;
			}
		}

		if (
			!empty( $this->user->get_data('language') ) && 
			$this->user->get_data('offer') == '1' && 
			!empty( $this->user->get_data('phone') ) &&
			! $this->bot->checkChatmember()
		) {
			$this->user->command_text = '/send_subscribe';
		}

		$this->user->command = $this->command->get_command( $this->user->command_text, $by_key );

		if ( !empty( $this->user->get_data('language') ) && $this->user->get_data('offer') != '1' && $offer ) {
			$this->user->command = $this->command->get_command( '/oferta', 'command_set' );
		}

		if ( 
			(
				!empty( $this->user->get_data('language') ) && 
				$this->user->get_data('offer') == '1' && 
				empty( $this->user->get_data('phone') ) && 
				$phone
			)
		) {
			$this->user->command = $this->command->get_command( '/phone', 'command_set' );
			$keyboard = [
                [
                    [
                        'text' => lang('send_phone'),
                        'request_contact' => true
                    ]
                ]
            ];
		}

		if ( !empty( $this->user->get_data('language') ) && empty( $this->user->get_data('phone') ) && !empty( $this->telegram->get_chatId() ) ) {
			$query = $this->db->get_where('contest_votes', [
				'chat_id' => $this->telegram->get_chatId()
			]);

			if ( $query->num_rows() > 0 ) {
				$this->user->command = $this->command->get_command( '/phone', 'command_set' );
				$keyboard = [
	                [
	                    [
	                        'text' => lang('send_phone'),
	                        'request_contact' => true
	                    ]
	                ]
	            ];
			}
		}

		if ( isset( $this->user->command['command_set'] ) && $this->user->command['command_set'] == '/phone' ) {
			$keyboard = [
                [
                    [
                        'text' => lang('send_phone'),
                        'request_contact' => true
                    ]
                ]
            ];
		}

		if ( is_array( $this->user->command ) ) {

			if ( !empty( $this->user->command['function'] ) && $function_active ) {
				$this->user->active_function = $this->user->command['function'];
				return $this->call_user_function( 'run' );
			}
			$childrens = $this->command->get_chilrens( $this->user->command['command_id'] );
			if ( $childrens ) {
				$keyboard = $this->menu->generate_keyboard( $childrens, $this->user->command['chunk'], ( $this->user->command['first_command'] == '1' ) );
				$this->user->last_command = $this->user->command_text;
			}
			if ( !empty( $this->user->command['inline_keyboard'] ) ) {
				$this->user->command['inline_keyboard'] = json_decode( $this->user->command['inline_keyboard'], TRUE );
				$inline_keyboard = $this->menu->generate_inline_keyboard( $this->user->command['inline_keyboard'] );
				$this->telegram->set_inlineKeyboard( $inline_keyboard );
				
				if ( isset( $keyboard ) ) {
					$this->command->send_command( $this->user->command );
					$this->telegram->reset('reply_markup')->set_replyKeyboard( $keyboard, lang('select_section') );
					$this->telegram->send_message( lang( 'continue' ) );
				}else{
					$this->command->send_command( $this->user->command );
				}
			}else{
				if ( isset( $keyboard ) ) {
					$this->telegram->set_replyKeyboard( $keyboard, lang('select_section') );
				}
				$this->command->send_command( $this->user->command );
			}
		}else{
			//$this->telegram->send_message( lang('undefined_action') );
		}
	}

	public function load_user_function(){
		if ( $this->load->is_telegram_module_exists( $this->user->active_function ) && $this->module->is_active( $this->user->active_function ) ) {
			$this->load->module_model($this->user->active_function, 'hook');
			$this->module_hook->load( $this->user->active_function );
			return TRUE;
		}

		return FALSE;
	}

	public function call_user_function( $method ){
		if ( $this->load->is_model_loaded( 'module_hook' ) ) {
			if(method_exists($this->module_hook, $method)){
				return $this->module_hook->$method();
			}
		}else if ( $this->load_user_function() ) {
			$this->call_user_function( $method );
		}

		return FALSE;
	}

	public function test(){
		print_r( $this->cache->file->get_metadata('list.commands') );
	}

	public function check_channels(){
		$channels = $this->db->get_where('channels', [
			'status' => '1',
			'subscription' => '1',
		]);

		if ( $channels->num_rows() > 0 ) {

			$this->not_subscribed_channels = [];

			foreach ($channels->result_array() as $channel) {
				$chatmem = $this->telegram->get_chatMember($channel['chat_username'], $chat_id);
        		if ( empty( $chatmem['result']['status'] ) ) {
        			return FALSE;
        		}
        		if(!in_array($chatmem['result']['status'], ['creator', 'creator', 'administrator', 'member'])){
            		$this->not_subscribed_channels[] = $channel;
        		}
			}

			if ( !empty( $this->not_subscribed_channels ) ) {
				return FALSE;
			}
		}

		return TRUE;
	}

	public function checkChatmember( $chat_id = NULL ){

		if ( is_null( $chat_id ) ) $chat_id = $this->user->chat_id;

		if ( ! $channels = $this->cache->file->get( 'list.channels' ) ) {
			$channels = $this->db->get_where('channels', [
				'channel_status' => '1',
				'channel_subscription' => '1',
			]);

			if ( $channels->num_rows() > 0 ) {
				$this->cache->file->save( 'list.channels', json_encode( $channels->result_array() ), 86400);
			}else{
				return TRUE;
			}
		}

		if ( is_string( $channels ) ) {
			$channels = json_decode( $channels, TRUE );
		}
		

		if ( !empty( $channels ) ) {

			$this->not_subscribed_channels = [];

			foreach ($channels as $channel) {
				$chatmem = $this->telegram->get_chatMember($channel['channel_chat_username'], $chat_id);
        		if ( empty( $chatmem['result']['status'] ) ) {
        			return FALSE;
        		}
        		if(!in_array($chatmem['result']['status'], ['creator', 'creator', 'administrator', 'member'])){
            		$this->not_subscribed_channels[] = $channel;
        		}
			}

			if ( !empty( $this->not_subscribed_channels ) ) {
				return FALSE;
			}
		}

		return TRUE;
	}

	public function sendSubsCribeMessage(){
		$buttons = [];

        foreach ($this->not_subscribed_channels as $n_channel) {
            $buttons[] = [
                [
                    "text" => $n_channel['channel_name'],
                    "url" => 'https://t.me/'.ltrim( $n_channel['channel_chat_username'], '@' )
                ]
            ];
        }

        $buttons[] = [
            [
                "text" => lang('subscribed_button'),
                "callback_data" => "channel=subscribed"
            ]
        ];

        $this->telegram->send_chatAction('typing')->set_inlineKeyboard($buttons)->send_message( lang('check_chatmember') );
		exit(1);
	}

	public function updateSubsCribeMessage(){
		$buttons = [];

        foreach ($this->not_subscribed_channels as $n_channel) {
            $buttons[] = [
                [
                    "text" => $n_channel['channel_name'],
                    "url" => 'https://t.me/'.ltrim( $n_channel['channel_chat_username'], '@' )
                ]
            ];
        }

        $buttons[] = [
            [
                "text" => lang('subscribed_button'),
                "callback_data" => "channel=subscribed"
            ]
        ];

        $req = $this->telegram->request('editMessageText', [
            'chat_id' => $this->user->updates['callback_query']['message']['chat']['id'],
            'message_id' => $this->user->updates['callback_query']['message']['message_id'],
            'reply_markup' => [
                'inline_keyboard' => $buttons
            ],
            'text' => lang('check_chatmember'),
            'parse_mode' => 'html',
            'disable_web_page_preview' => true
        ]);
		exit(1);
	}

	public function remove_keyboard( $action ){
		switch ($action) {
			case 'phone':
				$this->telegram->set_replyKeyboard([], '', TRUE)->send_message( lang('phone_saved') );
			break;

			case 'answer_type':
				$this->telegram->set_replyKeyboard([], '', TRUE)->send_message( lang('reply_has_been_accepted') );
			break;
		}
	}

	public function contact(){
		$this->telegram->set_chatId( $this->user->updates['message']['chat']['id'] );
		$this->user->set_chatId( $this->user->updates['message']['chat']['id'] );
		$phone_number = $this->user->updates["message"]["contact"]["phone_number"];

		$language = $this->user->get_data('language') ?? getDefaultLanguage();
		$this->lang->load( 'hook',  $language );

		if (
			!empty( $this->user->updates["message"]["contact"]["user_id"] ) && 
			$this->user->updates["message"]["contact"]["user_id"] == $this->user->updates["message"]["chat"]["id"]
		) {
			if ( validate_phone( clear_phone( $phone_number ) ) ) {
				$this->user->set_data( 'phone', clear_phone( $phone_number ) );
	            $this->remove_keyboard('phone');
	            if ( !$this->bot->checkChatmember() ) {
	            	$this->bot->messageText( '/send_subscribe', $this->user->chat_id, 'command_set' );
	            }else{
	            	$this->bot->messageText( '/main', $this->user->chat_id, 'command_set' );
	            }
	        }else{
	        	$this->bot->messageText( '/phone_wrong', $this->user->chat_id, 'command_set', FALSE, TRUE, FALSE );
	        }
		}else{
			$this->bot->messageText( '/phone_wrong', $this->user->chat_id, 'command_set', FALSE, TRUE, FALSE );
		}
	}

}

/* End of file Bot_model.php */
/* Location: ./application/models/hook/Bot_model.php */