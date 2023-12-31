<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats_model extends CI_Model {
	public function __construct() {
        parent::__construct();
        $this->table = 'poll';
        $this->column_order = array(null, 'poll.*', 'users.username', 'users.first_name', 'users.last_name', 'poll_answers.answer');
        $this->column_search = array();
        $this->order = array('poll.id' => 'asc');
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
        $this->db->select( implode( ',', $this->column_order) );
        $this->db->from($this->table);
 		$this->db->join('users', 'users.chat_id = poll.chat_id');
 		$this->db->join('poll_answers', 'poll_answers.id = poll.answer_id');
        
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

        if(isset($postData['question_id'])){
            $this->db->where_in('poll.question_id', $postData['question_id']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

	public function get_answers( $question_id ){
		$query = $this->db->query("SELECT poll.id, poll_answers.answer, poll.answer_id, CONCAT('#', poll.answer_id) as hashtag_answer_id, COUNT(*) AS total, 100 * COUNT(*)/s.total_sum AS percentage
			FROM poll 
			CROSS JOIN (
				SELECT COUNT(*) AS total_sum 
			    FROM poll
			    WHERE poll.question_id = ".$this->db->escape($question_id)."
			) AS s
			INNER JOIN poll_answers ON poll_answers.id=poll.answer_id
			WHERE poll.question_id = ".$this->db->escape($question_id)."
			GROUP BY poll.answer_id;");

		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		}

		return [];
	}

	public function get_series( $id ){
		$query = $this->db->query("SELECT
			COALESCE( COUNT(poll.id), 0 ) AS count,
			DAYNAME( FROM_UNIXTIME( poll.date ) ) as dayname,
			DATE( FROM_UNIXTIME( poll.date ) ) as day,
			poll.answer_id,
			poll_answers.answer
		FROM poll
		INNER JOIN poll_answers ON poll.answer_id=poll_answers.id
		WHERE 
			YEARWEEK( FROM_UNIXTIME( poll.date ) ) = YEARWEEK( NOW() )
			AND poll.question_id = ".$this->db->escape($id)."
		GROUP BY day, answer;");

		if ( $query->num_rows() > 0 ) {
			$query = $query->result_array();

			$result = [];

			foreach ($query as $row) {
				$hash = md5( $row['answer'] );
				if ( array_key_exists( $hash, $result ) ) {
					$result[ $hash ]['data'][] = $row['count'];
				}else{
					$result[ $hash ] = [
						'name' => '#'.$row['answer_id'],
						'data' => [ $row['count'] ],
					];
				}
			}

			return array_values( array_map(function($x) {
				for ($i=0; $i < 7; $i++)
					if ( !array_key_exists($i, $x['data']) ) $x['data'][$i] = 0;
				return $x;
			}, $result) );
		}

		return [];
	}

	public function get_list( $id ){
		
	}
}

/* End of file Stats_model.php */
/* Location: ./application/models/polls/inline/Stats_model.php */