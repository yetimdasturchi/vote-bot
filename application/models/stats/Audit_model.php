<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {
	public function getUsers(){
		$query = $this->db->query("SELECT
			(SELECT COUNT(id) 
				FROM users) count,
			(SELECT COUNT(id) 
				FROM referrals) referrals,
			(SELECT COUNT(id) 
				FROM users 
				WHERE language IN ('uzbek', '0') OR language IS NULL ) uzbek,
			(SELECT COUNT(id) 
				FROM users 
				WHERE language = 'russian') russian");

		$query = $query->row_array();

		foreach ($query as $k => $v) {
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}

	public function getVotes(){
		$query = $this->db->query('SELECT (SELECT COUNT(id) 
		          FROM contest_votes ) count,
			(SELECT COUNT(id) 
		          FROM contest_votes
            		WHERE check_status = 1) checked,
			(SELECT COUNT(id) 
		          FROM contest_votes
            		WHERE check_status = 0) unchecked,
			(SELECT COUNT(id) 
		          FROM contest_votes
            		WHERE check_status = 2) invalid');

		$query = $query->row_array();
		
		foreach ($query as $k => $v) {
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}

	public function getVotesFixed(){
		$query = $this->db->query('SELECT
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            GROUP BY chat_id
		        ) src
		    ) as allv,
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 1
		            GROUP BY chat_id
		        ) src
		    ) as success,
		    (SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 0
		            GROUP BY chat_id
		        ) src
		    ) as process,
		    (SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 2
		            GROUP BY chat_id
		        ) src
		    ) as ignored');
		$query = $query->row_array();
		
		foreach ($query as $k => $v) {
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}
}

/* End of file Audit_model.php */
/* Location: ./application/models/stats/Audit_model.php */