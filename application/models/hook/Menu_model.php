<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
	public function generate_keyboard( $arr=[], $chunk=2, $first_command=FALSE ){
		$keys = [];
		foreach ($arr as $command) $keys[] = $command['command_set'];

		if ( !$first_command ) {
			$keys[] = lang('back_button');
		}

		return array_chunk($keys, $chunk);
	}

	public function generate_inline_keyboard( $arr=[] ){
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

				case 'switch_inline_query':
					$reflink = $GLOBALS['system_config']['db_hostname'];
					$referrer_link = "https://t.me/".ltrim($GLOBALS['system_config']['bot_username'], '@')."?start=r-" . $this->user->chat_id;
					$value['value'] = str_replace('{reflink}', $referrer_link, $value['value']);
					$tmp['switch_inline_query'] = $value['value'];
				break;

				case 'webapp':
					$this->load->library('Encryptor');

					$url = parse_url( $value['value'] );

					$params = [
						'language' => $this->user->get_data('language'),
						'chat_id' => $this->user->chat_id,
						'expire' => time() + ( 86400 * 30 ),
					];

					$hash = $this->encryptor->xorEncryptArray($params);

					$this->user->set_data('hash', $hash);

					$add_params = [
						'hash' => $hash,
						'chat_id' => $this->user->chat_id,
					];

					if ( !empty( $url['query'] ) ) {
						parse_str($url['query'], $query);
						$url['query'] = array_merge($query, $add_params);
					}else{
						$url['query'] = $add_params;
					}

					$url = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . http_build_query( $url['query'] );
					//$this->telegram->send_message($url);
					$tmp['web_app'] = [
						'url' => $url
					];
				break;
			}

			$tmp['text'] = $value['name'];

			$keyboard[] = [ $tmp ];
		}

		return $keyboard;
	}

	public function back(){
		$last_command = $this->user->get_data('last_command');
		$last_command = $this->command->get_last_command( $last_command );
		if ( $last_command ) {
			$this->user->command_text = $last_command;
		}else{
			return;
		}
	}
}

/* End of file Menu_model.php */
/* Location: ./application/models/bot/Menu_model.php */