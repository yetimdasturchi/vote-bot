<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_model extends CI_Model {
	function __construct() {
        $this->table = 'contest_queue q';
        $this->column_order = array();
        $this->column_search = array('q.chat_id', 'u.username', 'u.first_name', 'u.last_name', 'c.name', 'u.phone');
        $this->order = array('q.send_date' => 'desc');
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
        $this->db->select( "q.id, q.chat_id, q.expire, q.expire, q.send_date, q.answered, q.sended, q.tg_status, q.status, u.id as u_id, u.username as u_username, u.phone as u_phone, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, pu.name as uz_name, puc.name as uzc_name, pr.name as ru_name, pe.name as en_name" );
        $this->db->from($this->table);
 		$this->db->join('users u', 'u.chat_id = q.chat_id');
 		$this->db->join('contests c', 'c.id = q.contest');
 		$this->db->join('poll_questions pu', 'pu.id = q.poll_uzbek', 'left');
 		$this->db->join('poll_questions puc', 'puc.id = q.poll_uzbek_cyr', 'left');
 		$this->db->join('poll_questions pr', 'pr.id = q.poll_russian', 'left');
 		$this->db->join('poll_questions pe', 'pe.id = q.poll_eglish', 'left');
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
            $this->db->where_in('q.chat_id', $postData['chat_id']);
        }

        if ( isset( $postData['poll_status'] ) && $postData['poll_status'] != '' ) {
        	$status_filter = explode('|', $postData['poll_status']);
            $status_filter_count = count( $status_filter );
            
            if ( $status_filter_count == 1 ) {
                if ( $status_filter[0] == '0' ) {
                    $this->db->where('q.status', '0');
                    $this->db->where('q.answered', '0');
                    $this->db->where('q.expire >', time() ) ;
                }else if ( $status_filter[0] == '1' ) {
                    $this->db->where('q.status', '1');
                }else if ( $status_filter[0] == '2' ) {
                    $this->db->where('q.status', '0');
                    $this->db->where('q.expire <=', time() ) ;
                }
            }else{
                if ( in_array('2', $status_filter) ) {
                    
                    if (($key = array_search('2', $status_filter)) !== false) {
                        $status_filter[$key] = '0';
                    }
                    $this->db->where('q.expire <=', time() ) ;
                    //$this->db->where('q.sended', '1' ) ;
                }else{
                    $this->db->where('q.sended', '1' ) ;
                }

                $this->db->where_in('q.status', array_unique($status_filter));
            }
            
        }

        if(!empty($postData['poll_ids'])){
        	$this->db->group_start();
            $this->db->where_in('q.poll_uzbek', explode('|', $postData['poll_ids']));
            $this->db->or_where_in('q.poll_russian', explode('|', $postData['poll_ids']));
            $this->db->group_end();
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function exportData( $poll_ids = '', $qstatus = '', $term = '' ){
        ini_set('memory_limit', -1);
        $this->_get_datatables_query([
            'poll_ids' => $poll_ids,
            'poll_status' => $qstatus,
            'search' => [
                'value' => $term
            ],
        ]);

        $query = $this->db->get();
        $result = $query->result_array();

        $this->load->library('xlsxgen');

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

            if ( !empty( $x['uz_name'] ) ) {
                $question = $x['uz_name'];
            }else if ( !empty( $x['uzc_name'] ) ) {
                $question = $x['uzc_name'];
            }else if ( !empty( $x['ru_name'] ) ) {
                $question = $x['ru_name'];
            }else if ( !empty( $x['en_name'] ) ) {
                $question = $x['en_name'];
            }else{
                $question = "-";
            }

            $x['question'] = $question;

            $x['u_name'] = !empty( $u_name ) ? $u_name : $x['chat_id'];

            if ( $x['expire'] <= time() && $x['answered'] == '0' ) {
                $x['q_status'] = "Javob bermagan";
            }

            if ( $x['answered'] > 0 ) {
                $x['answered'] = date('d.m.Y (H:i)', $x['answered']);
                $x['q_status'] = "Javob bergan";
            }else{
                $x['answered'] = "-";
            }

            if ( empty( $x['q_status'] ) ) {
                $x['q_status'] = "Jarayonda";
            }

            if ( !empty( $x['u_phone'] ) ) {
                $x['u_phone'] = format_phone( $x['u_phone'] );
            }else{
                $x['u_phone'] = "-";
            }

            $x['send_date'] = date('d.m.Y (H:i)', $x['send_date']);

            return [
                $x['id'],
                $x['u_name'],
                $x['u_phone'],
                $x['question'],
                $x['send_date'],
                $x['answered'],
                $x['q_status']
            ];
        }, $result);

        $questions = [
            ['ID', 'FOYDALANUVCHI', 'TELEFON RAQAM', 'SAVOL', 'YUBORISH VAQTI', 'JAVOB BERILGAN', 'HOLAT' ]
        ];

        $xlsx = Xlsxgen::fromArray( array_merge($questions, $result) );
        $filename = 'savollar_'.date('Y_m_d_H_i_s').'.xlsx';

        $xlsx->saveAs(FCPATH . 'tmp/' . $filename);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'removed' => "Eksport muvaffaqiyatli amalga oshirildi"
                ],
                '_callback' => "function(){window.open('".base_url('client/question/export?filename='.$filename)."', '_blank');}"
            ])
        );
    }

    public function resend( $poll_ids, $term ){
        $this->db->select( "q.id, q.chat_id, q.expire, q.expire, q.send_date, q.answered, q.sended, q.tg_status, q.status, u.id as u_id, u.username as u_username, u.phone as u_phone, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, pu.name as uz_name, puc.name as uzc_name, pr.name as ru_name, pe.name as en_name" );
        $this->db->from($this->table);
        $this->db->join('users u', 'u.chat_id = q.chat_id');
        $this->db->join('contests c', 'c.id = q.contest');
        $this->db->join('poll_questions pu', 'pu.id = q.poll_uzbek', 'left');
        $this->db->join('poll_questions puc', 'puc.id = q.poll_uzbek_cyr', 'left');
        $this->db->join('poll_questions pr', 'pr.id = q.poll_russian', 'left');
        $this->db->join('poll_questions pe', 'pe.id = q.poll_eglish', 'left');
        $i = 0;
        foreach($this->column_search as $item){
            if( $term ){
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $term);
                }else{
                    $this->db->or_like($item, $term);
                }
                
                if(count($this->column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }

        $this->db->where('q.expire <=', time() ) ;
        $this->db->where('q.status', '0');

        if(!empty( $poll_ids )){
            $this->db->group_start();
            $this->db->where_in('q.poll_uzbek', explode('|', $poll_ids));
            $this->db->or_where_in('q.poll_russian', explode('|', $poll_ids));
            $this->db->group_end();
        }

        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);

        $query = $this->db->get();
        $result = $query->result_array();

        if ( !empty( $result ) ) {
            $send_date = time() + 500;
            $x = 0;
            foreach ($result as $row) {
                if ( $x == 5 ) {
                    $send_date += 1;
                    $x=0;   
                }

                $this->db->update('contest_queue', [
                    'expire' => $send_date + 64800,
                    'send_date' => $send_date,
                    'sended' => '0',
                    'status' => '0',
                ], [
                    'id' => $row['id']
                ]);
                $x++;
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [
                    'removed' => "Savollar qayta yuborish jarayoniga qo'shildi"
                ],
                '_callback' => "function(){\$dtables['client_questions'].ajax.reload(null, false);}"
            ])
        );
    }
}

/* End of file Question_model.php */
/* Location: ./application/models/client/Question_model.php */