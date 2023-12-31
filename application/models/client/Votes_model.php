<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votes_model extends CI_Model {
	function __construct() {
        $this->table = 'contest_votes v';
        $this->column_order = array();
        $this->column_search = array('v.chat_id', 'u.username', 'u.first_name', 'u.last_name', 'c.name', 'm.name', 'n.name', 'u.phone');
        $this->order = array('v.id' => 'desc');
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

    private function _get_datatables_query( $postData, $additionals = FALSE ){
        if ( $additionals ) {
            $this->db->select( "v.id, v.chat_id, v.date, v.check_status, u.id as u_id, u.phone as u_phone, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, u.language as u_language, c.name_uzbek contest_name, m.name_uzbek as member_name, n.name_uzbek as nomination_name, a.city, a.age, a.gender" );
        }else{
            $this->db->select( "v.id, v.chat_id, v.date, v.check_status, u.id as u_id, u.phone as u_phone, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, m.name as member_name, n.name as nomination_name" );    
        }
        $this->db->from($this->table);
 		$this->db->join('users u', 'u.chat_id = v.chat_id');
 		$this->db->join('contests c', 'c.id = v.contest');
 		$this->db->join('members m', 'm.id = v.member');
 		$this->db->join('nominations n', 'n.id = v.nomination');
        
        if ( $additionals ) {
            $this->db->join('additional_fields a', 'a.user_id = u.id', 'left');
        }

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

        if(isset($postData['chat_id'])){
            $this->db->where_in('v.chat_id', $postData['chat_id']);
        }

        if ( isset( $postData['vstatus'] ) && $postData['vstatus'] != '' ) {
            $this->db->where_in('v.check_status', explode('|', $postData['vstatus']));
        }

        if ( !empty( $postData['vnomination'] ) ) {
            $this->db->where_in('v.nomination', explode('|', $postData['vnomination']));
        }

        if ( !empty( $postData['vmember'] ) ) {
            $this->db->where_in('v.member', explode('|', $postData['vmember']));
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function exportData( $vstatus, $vnomination, $vmember, $term ){
        ini_set('memory_limit', -1);
        $this->load->library('xlsxgen');

        $this->_get_datatables_query([
            'vstatus' => $vstatus,
            'vnomination' => $vnomination,
            'vmember' => $vmember,
            'search' => [
                'value' => $term
            ],
        ], TRUE);

        $query = $this->db->get();
        $result = $query->result_array();

        $result = array_map(function($x){
            if ( !empty( $x['u_first_name'] ) ) {
                $u_name = $x['u_first_name'];               
            }

            if ( !empty( $x['u_last_name'] ) ) {
                $u_name .= $x['u_last_name'];
            }

            if ( !empty( $x['u_username'] ) ) {
                $u_name .= " ( @{$x['u_username']} )";
            }
            
            $u_name = trim( $u_name );

            if ( mb_strlen( $u_name, "UTF-8" ) > 52 ) {
                $u_name = "Excepted";
            }

            $x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

            $x['date'] = date("d.m.Y (H:i)", $x['date']);

            switch ( $x['check_status'] ) {
                case '1':
                    $x['check_status'] = "Tekshirilgan";
                break;

                case '2':
                    $x['check_status'] = "Bekor qilingan";
                break;
                
                default:
                    $x['check_status'] = "Jarayonda";
                break;
            }

            if ( !empty( $x['u_phone'] ) ) {
                $x['u_phone'] = format_phone( $x['u_phone'] );
            }else{
                $x['u_phone'] = "-";
            }

            switch ($x['u_language']) {
                case 'russian':
                    $x['u_language'] = "Rus";
                break;
                
                default:
                    $x['u_language'] = "O'zbek";
                break;
            }

            if ( $x['city'] == "Андижанская область" ) {
                $x['city'] = "Andijon viloyati";
            }else if ( $x['city'] == "Бухарская область" ) {
                $x['city'] = "Buxoro viloyati";
            }else if ( $x['city'] == "Ферганская область" ) {
                $x['city'] = "Fargʻona viloyati";
            }else if ( $x['city'] == "Джизакская область" ) {
                $x['city'] = "Jizzax viloyati";
            }else if ( $x['city'] == "Хорезмская область" ) {
                $x['city'] = "Xorazm viloyati";
            }else if ( $x['city'] == "Наманганская область" ) {
                $x['city'] = "Namangan viloyati";
            }else if ( $x['city'] == "Навоийская область" ) {
                $x['city'] = "Navoiy viloyati";
            }else if ( $x['city'] == "Кашкадарьинская область" ) {
                $x['city'] = "Qashqadaryo viloyati";
            }else if ( $x['city'] == "Республика Каракалпакстан" ) {
                $x['city'] = "Qoraqalpogʻiston Respublikasi";
            }else if ( $x['city'] == "Самаркандская область" ) {
                $x['city'] = "Samarqand viloyati";
            }else if ( $x['city'] == "Сырдарьинская область" ) {
                $x['city'] = "Sirdaryo viloyati";
            }else if ( $x['city'] == "Сурхандарьинская область" ) {
                $x['city'] = "Surxondaryo viloyati";
            }else if ( $x['city'] == "г.Ташкент" ) {
                $x['city'] = "Toshkent shahri";
            }else if ( $x['city'] == "Ташкентская область" ) {
                $x['city'] = "Toshkent viloyati";
            }

            if ( in_array($x['gender'], ['🧑 Erkak', '🧑 Мужчина']) ) {
                $x['gender'] = "Erkak";
            }else if ( in_array($x['gender'], ['👩 Ayol', '👩 Женщина']) ) {
                $x['gender'] = "Ayol";
            }else{
                $x['gender'] = "";
            }

            return [
                $x['id'],
                $x['u_name'],
                $x['u_phone'],
                $x['nomination_name'],
                $x['member_name'],
                $x['check_status'],
                $x['date'],
                $x['gender'],
                $x['city'],
                $x['age'],
                $x['u_language'],
            ];
        }, $result);

        $questions = [
            ['ID', 'FOYDALANUVCHI', 'TELEFON RAQAM', 'NOMINATSIYA', 'ISHTIROKCHI', 'HOLAT', 'VAQT', 'JINS', 'HUDUD', 'YOSH', 'TIL' ]
        ];

        $xlsx = Xlsxgen::fromArray( array_merge($questions, $result) );
        $filename = 'ovozlar_'.date('Y_m_d_H_i_s').'.xlsx';

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
}

/* End of file Votes_model.php */
/* Location: ./application/models/client/Votes_model.php */