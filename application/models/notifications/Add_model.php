<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function process(){
		$this->set_rules();	
		if ( $this->form_validation->run() ) {
			$language = $this->input->post('language');
			$phone = $this->input->post('phone');

			$message = $this->input->post('message');
			$to = $this->input->post('to');
			$buttons = $this->input->post('buttons');
			$selected_users = $this->input->post('selected_users');

			if ( $_FILES["file"]["error"] == 0 ) {
                
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('manager_telegram') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ lang('notifications_file_not_uploaded') ]
                        ])
                    );
                    exit(0);
                }
            }

            if ( !empty( $selected_users ) ) {
            	$users = $selected_users;
            }else{
            	if ( $to == 'users' ) {
	            	$this->db->select('chat_id, id');
	            	if ( !empty( $language ) ) {
	            		$this->db->where('language', $language);
	            	}
	            	if ( !empty( $phone ) ) {
	            		if ( $phone == 'not_empty' ) {
	            			$this->db->where('phone IS NOT NULL', null, false);
	            		}else if ( $phone == 'is_empty' ) {
	            			$this->db->where('phone IS NULL', null, false);
	            		}
	            	}
					$users = $this->db->get('users');
	            
					if ( $users->num_rows() > 0 ) {
						$users = $users->result_array();
						$users = array_map(function($x){
							return $x['chat_id'];
						}, $users);
					}else $users = [];
	            }

	            if ( preg_match('/archive_(\d+)/', $to, $matches) ) {
	            	$this->db->select('users_archive_users_telegram');
	            	$this->db->where('users_archive_users_category', $matches[1]);
	            	$users = $this->db->get('users_archive_users');
					if ( $users->num_rows() > 0 ) {
						$users = $users->result_array();
						$users = array_map(function($x){
							return $x['users_archive_users_telegram'];
						}, $users);
					}else $users = [];
	            }
            }

            if ( isset( $users ) && !empty( $users ) ) {
            	$data = "<?php\nreturn false;\n?>";
				file_put_contents( APPPATH . 'config/notifications.php', $data);

            	if ( !folder_exist( FCPATH . 'tmp/notifications/' ) ) {
					mkdir( FCPATH . 'tmp/notifications/' );	
				}

				$filetime = time();
				foreach ($users as $user) {
					$filetime += rand(1, 2);
					$filename = FCPATH . 'tmp/notifications/m_' . $user .'_'. md5( $user.time() ) . '.json';
					$data = [
						'chat_id' => $user,
						'message' => $message,
					];

					if ( isset( $file_id ) && is_array( $file_id ) ) {
						$data = array_merge($data, $file_id);	
					}

					if ( isset( $buttons ) && !empty($buttons) ) {
						$data = array_merge($data, ['buttons' => $buttons]);	
					}

					file_put_contents( $filename , json_encode( $data ));
					touch($filename, $filetime);
				}

				return $this->output
	                ->set_content_type('application/json')
	                ->set_status_header(200)
	                ->set_output(json_encode([
	                    'status' => true,
	                    'reset' => true,
	                    'messages' => [
	                        'addedd' => lang('notifications_successfully_added')
	                    ],
	                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('notifications/records')."' }, 1000);}"
	                ])
	            );
            }else{
            	return $this->output
		    		->set_content_type('application/json')
		    		->set_status_header(400)
		    		->set_output(json_encode([
		        		'status' => false,
		        		'messages' => lang('notifications_users_not_found')
		    		])
		    	);
            }
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => $this->form_validation->error_array()
		    	])
		    );
		}
	}

	public function set_rules(){
		$this->form_validation->set_rules('to', lang('notifications_to'), [
			['to_check', [$this, 'to_check']]
		], ['to_check' => lang('notifications_to_field_incorrect')]);
		$this->form_validation->set_rules('language', lang('notifications_language'), [
			['language_check', [$this, 'language_check']]
		], ['language_check' => lang('notifications_language_field_incorrect')]);
		$this->form_validation->set_rules('message', lang('notifications_message'), [
			['message_check', [$this, 'message_check']]
		], ['message_check' => lang('notifications_message_field_incorrect')]);

		$buttons = $this->input->post('buttons');
        
        if ( !empty( $buttons ) ) {
            $error = "";
            foreach ($buttons as $button) {
                if ( empty( $button['name'] ) ){
                    $error = lang('notifications_button_name_not_entered');
                    break;
                }
                if ( empty( $button['value'] ) ){
                    $error = lang('notifications_button_value_not_entered');
                    break;
                }
                if ( empty( $button['type'] ) ) {
                    $error = lang('notifications_button_type_not_selected');
                    break;
                }
                if ( !empty( $button['type'] ) && !in_array($button['type'], ['url', 'callback', 'webapp']) ) {
                    $error = lang('notifications_button_type_not_matched');
                    break;
                }
            }

            if ( !empty( $error ) ) {
                $this->output->set_content_type('application/json')
                    ->set_status_header(400)
                    ->_display(json_encode([
                        'status' => false,
                        'messages' => [$error]
                    ])
                );
                exit(0);
            }
        }
	}

	public function to_check(){
		$str = $this->input->post('to');
		if ( $str == 'users' ) return TRUE;

		if ( preg_match('/archive_(\d+)/', $str, $matches) ) {
			$query = $this->db->get_where('users_archive_categories', [
				'users_archive_category_id' => $matches[1]
			]);

			if ( $query->num_rows() > 0 ) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function language_check(){
		$str = $this->input->post('language');
		if ( empty( $str ) ) return TRUE;

		$languages = getLanguages(true);
		if ( in_array( $str, array_keys( $languages ) ) ) {
			return TRUE;
		}

		return FALSE;
	}

	public function message_check(){
		$str = $this->input->post('message');
		
		if ( !empty( $_FILES['file']['error'] ) && $_FILES['file']['error'] == 0 && mb_strlen($str, 'UTF-8') > 1024 ) {
			return FALSE;
		}else if (mb_strlen($str, 'UTF-8') > 4096){
			return FALSE;
		}

		return TRUE;
	}

	public function get_users(){
		$search = $this->input->get('search');
        $page = $this->input->get('page');

		$this->db->select('chat_id as id, CONCAT(first_name, \' \',last_name, \'(\', username, \')\') as text');
		$this->db->from('users');
		
		if ( !empty( $search ) ) {
            $this->db->like('phone', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('chat_id', $search);
        }

        if ( !empty( $page ) ) {
            $resultCount = 10;
            $end = ($page - 1) * $resultCount;
            $start = $end + $resultCount;

            $this->db->limit($start, $end);
        }

		$this->db->order_by('chat_id', 'asc');
		$this->db->limit(10);

		return $this->db->get()->result_array();
	}

}

/* End of file Add_mode.php */
/* Location: ./application/models/notifications/Add_mode.php */