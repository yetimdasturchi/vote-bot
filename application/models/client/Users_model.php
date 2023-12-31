<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	function __construct() {
        $this->table = 'users u';
        $this->column_order = array();
        $this->column_search = array('u.id','u.username','u.chat_id','u.first_name','u.last_name', 'u.phone', 'a.age');
        $this->order = array('registered' => 'desc');
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
        $this->db->select( "u.*, a.city, a.age, a.gender" );
        $this->db->from($this->table);
 		$this->db->join('additional_fields a', 'a.user_id = u.id', 'left');
 
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

        if ( !empty( $postData['ulanguage'] ) ) {
            $this->db->where_in('u.language', explode('|', $postData['ulanguage']));
        }

        if ( isset( $postData['ucity'] ) && $postData['ucity'] != '' ) {
            $this->db->where_in('a.city', $this->get_ct( explode('|', $postData['ucity'] ) ) );
        }

        if ( isset( $postData['ugender'] ) && $postData['ugender'] != '' ) {
            $this->db->where_in('a.gender', $this->get_gender( explode('|', $postData['ugender'] ) ) );
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
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

	public function get_columns(){
		$database = $this->db->escape($GLOBALS['system_config']['db_database']);
		$query = $this->db->query("SELECT COLUMN_NAME as xfield, COLUMN_COMMENT as name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ".$database." AND TABLE_NAME = 'additional_fields' AND COLUMN_NAME NOT IN ('id','user_id');");

		return $query->result_array();
	}

    public function exportData( $ulanguage, $ucity, $ugender, $term ){
        ini_set('memory_limit', -1);
        $this->load->library('xlsxgen');

        $this->_get_datatables_query([
            'ulanguage' => $ulanguage,
            'ucity' => $ucity,
            'ugender' => $ugender,
            'search' => [
                'value' => $term
            ],
        ]);

        $query = $this->db->get();
        $result = $query->result_array();

        $result = array_map(function($x){
            $name = "";

            if ( !empty( $x['first_name'] ) ) {
                $name = $x['first_name'];               
            }

            if ( !empty( $x['last_name'] ) ) {
                $name .= $x['last_name'];
            }

            if ( !empty( $x['username'] ) ) {
                $name .= " ( @{$x['username']} )";
            }
            
            $name = trim( $name );

            if ( mb_strlen( $name, "UTF-8" ) > 52 ) {
                $name = "Excepted";
            }

            $x['phone'] = empty( $x['phone'] ) ? "-" : format_phone($x['phone']);
            
            $x['username'] = !empty( $name ) ? $name : $x['chat_id'];

            switch( $x['language'] ) {
                case 'uzbek':
                    $x['language'] = 'O\'zbek';
                break;

                case 'russian':
                    $x['language'] = 'Rus';
                break;
                
                default:
                    $x['language'] = "-";
                break;
            }

            $x['registered'] = date("d.m.Y (H:i)", $x['registered']);
            $x['last_action'] = date("d.m.Y (H:i)", $x['last_action']);

            if ( empty( $x['age'] ) ) $x['age'] = '-';
            if ( empty( $x['gender'] ) ) $x['gender'] = '-';
            if ( empty( $x['city'] ) ) $x['city'] = '-';

            return [
                $x['id'],
                $x['chat_id'],
                $x['username'],
                $x['registered'],
                $x['last_action'],
                $x['language'],
                $x['phone'],
                $x['age'],
                $x['city'],
                $x['gender'],
            ];
        }, $result);

        $questions = [
            ['ID', 'CHAT ID', 'NOMI', 'RO\'YXATDAN O\'TGAN', 'SO\'NGGI AKTIVLIK', 'Til', 'TELEFON RAQAM', 'TUG\'ILGAN YIL', 'HUDUD', 'JINS' ]
        ];

        $xlsx = Xlsxgen::fromArray( array_merge($questions, $result) );
        $filename = 'foydalanuvchilar_'.date('Y_m_d_H_i_s').'.xlsx';

        $xlsx->saveAs(FCPATH . 'tmp/' . $filename);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'removed' => "Eksport muvaffaqiyatli amalga oshirildi"
                ],
                '_callback' => "function(){window.open('".base_url('client/votes/export?filename='.$filename)."', '_blank');}"
            ])
        );
    }

    public function get_additional_info( $id, $language ){
        $fields = $this->get_columns();

        $query = $this->db->get_where('additional_fields', [
            'user_id' => $id
        ])->row_array();

        $tmp = [];

        $additional_source = $this->getAdditionalSource( $fields, $language );
        
        foreach ($fields as $field) {
            $source = array_key_exists($field['xfield'], $additional_source) ? $additional_source[ $field['xfield'] ] : [];
            if ( !empty( $query ) && array_key_exists($field['xfield'], $query) && $query[ $field['xfield'] ] != '' ) {
                $tmp[] = [
                    'name' => $field['name'],
                    'field' => $field['xfield'],
                    'value' => $query[ $field['xfield'] ],
                    'source' => $source
                ];
            }else{
                $tmp[] = [
                    'name' => $field['name'],
                    'field' => $field['xfield'],
                    'value' => "",
                    'source' => $source
                ];
            }
        }

        return $tmp;
    }

    public function getAdditionalSource( $fields, $language ){
        if ( empty( $language ) ) {
            $language = getDefaultLanguage();
        }

        $tmp = [];
        foreach ($fields as $field) {
            $question = $this->db
                ->select('id')
                ->from('poll_questions')
                ->where('additional_field', $field['xfield'])
                ->where( 'language', $language )
                ->where('status', '1')->get();
        
            if ( $question->num_rows() > 0 ) {
                $question = $question->row_array();
                $answers = $this->db
                        ->select('id, answer')
                        ->from('poll_answers')
                        ->where('status', '1')
                        ->where('question_id', $question['id'])->get();
                if ( $answers->num_rows() > 0 ) {
                    $answers = $answers->result_array();
                    $tmp_answers = [];
                    foreach ($answers as $answer) {
                        $tmp_answers[ $answer['answer'] ] = $answer['answer'];
                    }

                    $tmp[ $field['xfield'] ] = $tmp_answers;
                }
            }
        }

        return $tmp;
    }

    public function edit( $id ) {
        $query = $this->db->get_where('additional_fields', [
            'user_id' => $id
        ]);

        $data = $this->input->post();
        
        if ( $query->num_rows() > 0 ) {
            $this->db->update('additional_fields', $data, [ 'user_id' => $id ]);
        }else{
            $data['user_id'] = $id;
            $this->db->insert('additional_fields', $data);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    "Ma'lumotlar muvaffaqiyatli yangilandi"
                ],
                '_callback' => 'function(){$(\'.bs-ajax-modal .modal-body, .bs-ajax-modal .modal-title\').html(\'\'); $(\'.bs-ajax-modal\').modal(\'hide\'); $(\'.bs-ajax-modal\').modal(\'hide\'); $dtables[\'client_users\'].ajax.reload();}'
            ])
        );
    }
}

/* End of file Users_model.php */
/* Location: ./application/models/client/Users_model.php */