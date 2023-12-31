<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records_model extends CI_Model {
	public function render( $data ){
		if ( !empty( $data ) ) {
			$tmp_data = [];

			foreach ($data as $file) {
				if ( file_exists( $file ) ) {
					$json = file_get_contents( $file );
					$json = json_decode( $json, TRUE );
					
					if ( empty( $json ) ) continue;

					$user = $this->db->get_where('users', [
						'chat_id' => $json['chat_id']
					]);

					if ( $user->num_rows() > 0 ) {
						$user = $user->row_array();
						$name = $user['first_name'] . ' '. $user['last_name'];
						if ( !empty( $user['username'] ) ) {
							$name .= " ( @{$user['username']} )";
						}
						if ( strlen( $name ) > 52 ) {
							$name = "Excepted";
						}
						$name = $name;
					}else{
						$name = '<span class="badge bg-danger">'.$json['chat_id'].'</span>';
					}

					if ( !empty( $json['type'] ) ) {
						$json['type'] = lang( 'notifications_type_' . $json['type'] );
					}else{
						$json['type'] = lang('notifications_type_text');
					}

					$json['type'] = "<code>{$json['type']}</code>";

					$tmp_data[] = [
						'user' => $name,
						'message' => "<span data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"".htmlentities( $json['message'] )."\"><span class=\"badge bg-secondary cursor-pointer\">Ko'rish</span></span>",
						'time' => date('Y-m-d | H:i:s', filemtime($file)),
						'type' => $json['type']
					];
				}
			}

			return $tmp_data;
		}

		return [];
	}

	public function clear(){
		$dir = opendir( FCPATH . 'tmp/notifications/' );
		while( false != ( $file = readdir( $dir ) ) ){
			if ( substr( $file,-5 ) == ".json" ){
				@unlink( FCPATH . 'tmp/notifications/' . $file );
			}
		}

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'addedd' => lang('notifications_successfully_cleared')
                ],
                '_callback' => "function(){ \$dtables[ 'notifications_records' ].draw(); }"
            ])
        );
	}

	public function play(){
		$data = "<?php\nreturn true;\n?>";
		file_put_contents( APPPATH . 'config/notifications.php', $data);

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'addedd' => lang('notifications_successfully_started')
                ],
                '_callback' => "function(){setTimeout( ()=> { location.reload() }, 1000);}"
            ])
        );
	}

	public function pause(){
		$data = "<?php\nreturn false;\n?>";
		file_put_contents( APPPATH . 'config/notifications.php', $data);

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'addedd' => lang('notifications_successfully_started')
                ],
                '_callback' => "function(){setTimeout( ()=> { location.reload() }, 1000);}"
            ])
        );
	}
}

/* End of file Records_model.php */
/* Location: ./application/models/notifications/Records_model.php */