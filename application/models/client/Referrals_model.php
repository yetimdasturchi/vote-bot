<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals_model extends CI_Model {

	private $rows = [];
	private $filtered = 0;

	public function getRows($postData){
        $this->_get_datatables_query($postData);
        return $this->rows;
    }

    public function countAll(){
        $query = $this->db->query("SELECT `r`.*, `o`.`username` as `o_username`, `o`.`first_name` as `o_first_name`, `o`.`last_name` as `o_last_name`, `o`.`phone` as `o_phone`, COUNT(s.id) as success, COUNT(i.id) as ignored, COUNT(p.id) as process, (select count(*) from referrals rr where r.owner_id = rr.owner_id) as count
        	FROM `referrals` `r`
        	JOIN `users` `u` ON `u`.`chat_id` = `r`.`chat_id`
        	JOIN `users` `o` ON `o`.`chat_id` = `r`.`owner_id`
        	LEFT JOIN `contest_votes` `s` ON `s`.`chat_id` = `r`.`chat_id` AND (s.check_status = 1)
        	LEFT JOIN `contest_votes` `i` ON `i`.`chat_id` = `r`.`chat_id` AND (i.check_status = 2)
        	LEFT JOIN `contest_votes` `p` ON `p`.`chat_id` = `r`.`chat_id` AND (p.check_status = 0)
        	GROUP BY r.owner_id
        	ORDER BY count DESC, success DESC, ignored DESC, process DESC");
		return $query->num_rows();
    }

    public function countFiltered($postData){
        return $this->filtered;
    }

    private function _get_datatables_query($postData){
        
        if( $postData['length'] != -1 ){
        	$start = $postData['start'];
        	$limit = $postData['length'];
        }else{
        	$start = 0;
        	$limit = 10;
        }

        //$query = $this->db->query( "SELECT notifications.id, notifications.chat_id, notifications.source, notifications.date, notifications.template FROM notifications ORDER BY date DESC LIMIT {$start}, $limit" );
        $query = $this->db->query( "SELECT `r`.*, `o`.`username` as `o_username`, `o`.`first_name` as `o_first_name`, `o`.`last_name` as `o_last_name`, `o`.`phone` as `o_phone`, COUNT(s.id) as success, COUNT(i.id) as ignored, COUNT(p.id) as process, (select count(*) from referrals rr where r.owner_id = rr.owner_id) as count
        	FROM `referrals` `r`
        	JOIN `users` `u` ON `u`.`chat_id` = `r`.`chat_id`
        	JOIN `users` `o` ON `o`.`chat_id` = `r`.`owner_id`
        	LEFT JOIN `contest_votes` `s` ON `s`.`chat_id` = `r`.`chat_id` AND (s.check_status = 1)
        	LEFT JOIN `contest_votes` `i` ON `i`.`chat_id` = `r`.`chat_id` AND (i.check_status = 2)
        	LEFT JOIN `contest_votes` `p` ON `p`.`chat_id` = `r`.`chat_id` AND (p.check_status = 0)
        	GROUP BY r.owner_id
        	ORDER BY count DESC, success DESC, ignored DESC, process DESC
		LIMIT {$start}, {$limit}" );
        $this->filtered = $query->num_rows();
        $this->rows = $query->result_array();
    }

}

/* End of file Referrals_model.php */
/* Location: ./application/models/client/Referrals_model.php */