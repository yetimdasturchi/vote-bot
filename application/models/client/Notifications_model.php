<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');

		$this->table = 'notifications_templates';
        $this->column_order = array(null, 'id','name','message','file','inline_keyboard');
        $this->column_search = array('name','message','inline_keyboard','file');
        $this->order = array('id' => 'desc');
	}

	public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countAll(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData){
         
        $this->db->from($this->table);
 
        $i = 0;
        foreach($this->column_search as $item){
            if($postData['search']['value']){
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                if(count($this->column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function edit_process( $id, $data ){
    	$this->set_rules();	

		if ( $this->form_validation->run() ) {
			$message = $this->input->post('message');
			$name = $this->input->post('name');
			$buttons = $this->input->post('buttons');
			if ( $_FILES["file"]["error"] == 0 ) {
                
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('client_chat_id') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ 'Faylni yuklashda xatolik' ]
                        ])
                    );
                    exit(0);
                }
            }

            $this->db->update('notifications_templates', [
            	'name' => $name,
            	'message' => $message,
            	'inline_keyboard' => !empty( $buttons ) ? json_encode( $buttons ) : '',
            	'file' => isset( $file_id ) ? json_encode( $file_id ) : $data['file'],
            ], [
            	'id' => $id
            ]);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => "Bildirishnoma uchun andoza muvaffaqiyatli tahrirlandi"
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('client/notifications/templates')."' }, 1000);}"
                ])
            );
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

	public function add_process(){
		$this->set_rules();	

		if ( $this->form_validation->run() ) {
			$message = $this->input->post('message');
			$name = $this->input->post('name');
			$buttons = $this->input->post('buttons');
			if ( $_FILES["file"]["error"] == 0 ) {
                
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('client_chat_id') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ 'Faylni yuklashda xatolik' ]
                        ])
                    );
                    exit(0);
                }
            }

            $this->db->insert('notifications_templates', [
            	'name' => $name,
            	'message' => $message,
            	'inline_keyboard' => !empty( $buttons ) ? json_encode( $buttons ) : '',
            	'file' => isset( $file_id ) ? json_encode( $file_id ) : ''
            ]);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => "Bildirishnoma uchun andoza muvaffaqiyatli qo'shildi"
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('client/notifications/templates')."' }, 1000);}"
                ])
            );
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
		$this->form_validation->set_rules('name', "Andoza nomi", 'required|alpha_numeric_spaces|min_length[4]|max_length[255]');
		$this->form_validation->set_rules('message', 'Xabar', [
			['message_check', [$this, 'message_check']]
		], ['message_check' => 'Xabar maydoni xato to‘ldirildi']);

		$buttons = $this->input->post('buttons');
        
        if ( !empty( $buttons ) ) {
            $error = "";
            foreach ($buttons as $button) {
                if ( empty( $button['name'] ) ){
                    $error = 'Tugma uchun matn kiritilmadi';
                    break;
                }
                if ( empty( $button['value'] ) ){
                    $error = 'Tugma uchun qiymat kiritilmadi';
                    break;
                }
                if ( empty( $button['type'] ) ) {
                    $error = 'Tugma turi tanlanmadi';
                    break;
                }
                if ( !empty( $button['type'] ) && !in_array($button['type'], ['url', 'callback', 'webapp']) ) {
                    $error = 'Ruxsat etilmagan tugma turi tanlandi';
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

	public function message_check(){
		$message = $this->input->post('message');
        
        if($_FILES["file"]["error"] == 0) {
            return TRUE;
        }

        if ( !empty( $message ) ) {
            return TRUE;
        }

        return FALSE;
	}

	public function get_ct( $keys = [] ){
    	$ct =  [
    		['Andijon viloyati', 'Андижанская область'],
    		['Buxoro viloyati', 'Бухарская область'],
    		['Fargʻona viloyati', 'Ферганская область'],
    		['Jizzax viloyati', 'Джизакская область'],
    		['Xorazm viloyati', 'Хорезмская область'],
    		['Namangan viloyati', 'Наманганская область'],
    		['Navoiy viloyati', 'Навоийская область'],
    		['Qashqadaryo viloyati', 'Кашкадарьинская область'],
    		['Qoraqalpogʻiston Respublikasi', 'Республика Каракалпакстан'],
    		['Samarqand viloyati', 'Самаркандская область'],
    		['Sirdaryo viloyati', 'Сырдарьинская область'],
    		['Surxondaryo viloyati', 'Сурхандарьинская область'],
    		['Toshkent shahri', 'г.Ташкент'],
    		['Toshkent viloyati', 'Ташкентская область']
    	];

    	if ( count( $keys ) > 0 ) {
    		$tmp = [];
    		foreach ($keys as $v) {
    			if ( array_key_exists($v, $ct) ) {
    				$tmp = array_merge($tmp, $ct[ $v ]);
    			}
    		}

    		return $tmp;
    	}

    	return $ct;
    }

    public function get_gender( $keys = [] ){
    	$gender =  [
    		['🧑 Erkak', '🧑 Мужчина'],
    		['👩 Ayol', '👩 Женщина']
    	];

    	if ( count( $keys ) > 0 ) {
    		$tmp = [];
    		foreach ($keys as $v) {
    			if ( array_key_exists($v, $gender) ) {
    				$tmp = array_merge($tmp, $gender[ $v ]);
    			}
    		}

    		return $tmp;
    	}

    	return $gender;
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

	public function getUsers( $to, $selected_users, $language, $phone, $ct, $gender, $voted = FALSE ) {
		if ( !empty( $selected_users ) ) {
			$users = $selected_users;
		}else if( $to == 'users' ) {
			$this->db->select( "u.*, a.city, a.age, a.gender" );
			$this->db->from('users u');
			$this->db->join('additional_fields a', 'a.user_id = u.id', 'left');

			if ( $voted ) {
				if ( $voted == '3' ) $voted = '0';
				$this->db->join('contest_votes c', 'c.chat_id = u.chat_id');
				$this->db->where('c.check_status', $voted);
			}

			if ( !empty( $language ) ) {
	            $this->db->where( 'u.language', $language );
	        }

	        if ( !empty( $phone ) ) {
        		if ( $phone == 'not_empty' ) {
        			$this->db->where('u.phone IS NOT NULL', null, false);
        		}else if ( $phone == 'is_empty' ) {
        			$this->db->where('u.phone IS NULL', null, false);
        		}
        	}

	        if ( isset( $ct ) && $ct != '' ) {
	            $this->db->where_in('a.city', $this->get_ct( [$ct] ) );
	        }

	        if ( isset( $gender ) && $gender != '' ) {
	            $this->db->where_in('a.gender', $this->get_gender( [$gender] ) );
	        }
	        $users = $this->db->get();
        
			if ( $users->num_rows() > 0 ) {
				$users = $users->result_array();
				$users = array_map(function($x){
					return $x['chat_id'];
				}, $users);
			}else $users = [];
		}else if ( preg_match('/archive_(\d+)/', $to, $matches) ) {
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

		return isset( $users ) ? array_unique( $users ) : [];
	}

	public function send(){
		ini_set('memory_limit', -1);

		$this->form_validation->set_rules('to', "Qabul qilivchi", [
			['to_check', [$this, 'to_check']]
		], ['to_check' => "Qabul qilivchi maydoni xato kiritildi"]);
		
		$this->form_validation->set_rules('language', "Til", [
			['language_check', [$this, 'language_check']]
		], ['language_check' => "Til maydoni xato kiritildi"]);

		if ( $this->form_validation->run() ) {
			$template = $this->input->post('template');

			$query = $this->db->get_where('notifications_templates', [
				'id' => $template
			]);

			if ( $query->num_rows() == 0 ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		'Andoza topilmadi, iltimos sahifani yangilab ko\'ring'
			        	],
			    	])
		    	);
			}

			$query = $query->row_array();

			$to = $this->input->post('to');
			$selected_users = $this->input->post('selected_users');
			
			$language = $this->input->post('language');
			$phone = $this->input->post('phone');
			$ct = $this->input->post('ct');
			$gender = $this->input->post('gender');
			$voted = $this->input->post('voted');

			$users = $this->getUsers( $to, $selected_users, $language, $phone, $ct, $gender, $voted );
			if ( empty( $users ) ) {
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(400)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'messages' => [
			        		'Foydalanuvchi topilmadi'
			        	],
			    	])
		    	);
			}

			$users = array_values(array_unique($users, SORT_REGULAR));

			$data = "<?php\nreturn false;\n?>";
			file_put_contents( APPPATH . 'config/notifications.php', $data);

			$tmp = [];
			$date = time();
			$source = preg_match('/archive_(\d+)/', $to) ? '0' : '1';
        	foreach ($users as $user) {
				$tmp[] = [
					'chat_id' => $user,
					'source' => $source,
					'date' => $date,
					'message' => $query['message'],
					'file' => $query['file'],
					'buttons' => $query['inline_keyboard'],
					'template' => $template,
				];
			}

			$this->db->insert_batch('notifications', $tmp); unset( $tmp );

			$this->db->update('notifications_templates', [
				'date' => time(),
				'all' => count( $users ),
				'success' => '0',
				'error' => '0',
				'last_send' => '0'
			], [
				'id' => $template
			]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
		        	'messages' => [
		        		'Bildirishnomalar muvaffaqiyatli qo\'shildi'
		        	],
		        	'_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('client/notifications/records')."' }, 1000);}"
		    	])
		    );
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

	public function clear(){
		$this->db->truncate('notifications');

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'addedd' => "Jarayondagi xabarlar muvaffaqiyatli tozalandi"
                ],
                '_callback' => "function(){ \$dtables[ 'client_nitifications_records' ].draw(); }"
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
                    'addedd' => "Bildirishnomalarni yuborish yoqildi"
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
                    'addedd' => "Bildirishnomalarni yuborish to'xtatildi"
                ],
                '_callback' => "function(){setTimeout( ()=> { location.reload() }, 1000);}"
            ])
        );
	}
}

/* End of file Notifications_model.php */
/* Location: ./application/models/client/Notifications_model.php */