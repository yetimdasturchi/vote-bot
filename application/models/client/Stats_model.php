<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats_model extends CI_Model {
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

	public function getVotesGroup(){
		$query = $this->db->query('SELECT
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            GROUP BY chat_id, check_status
		        ) src
		    ) as allv,
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 1
		            GROUP BY chat_id, check_status
		        ) src
		    ) as success,
		    (SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 0
		            GROUP BY chat_id, check_status
		        ) src
		    ) as process,
		    (SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM contest_votes
		            WHERE check_status = 2
		            GROUP BY chat_id, check_status
		        ) src
		    ) as ignored');
		$query = $query->row_array();
		
		foreach ($query as $k => $v) {
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}

	public function getUsers(){
		$query = $this->db->query("SELECT
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM users
		            GROUP BY chat_id
		        ) src
		    ) as all_users,
		    (SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					INNER JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE additional_fields.gender IN('🧑 Erkak', '🧑 Мужчина')
			        GROUP BY chat_id
			    ) src
			) as all_mans,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					INNER JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE additional_fields.gender IN('👩 Ayol', '👩 Женщина')
					GROUP BY chat_id
			    ) src
			) as all_womans,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					LEFT JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE additional_fields.gender IS NULL
					GROUP BY chat_id
			    ) src
			) as all_undefined");

		$query = $query->row_array();
		
		$sum = 0;
		foreach ($query as $k => $v) {
			$sum += $v;
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}

	public function getLanguage(){
		$query = $this->db->query("SELECT
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE language = 'uzbek'
			        GROUP BY chat_id
			    ) src
			) as all_uzbek,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE language = 'russian'
					GROUP BY chat_id
			    ) src
			) as all_russian,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE language IS NULL OR language = '0'
					GROUP BY chat_id
			    ) src
			) as all_undefined");
		$query = $query->row_array();
		
		$sum = 0;
		foreach ($query as $k => $v) {
			$sum += $v;
			$query[ $k ] = number_format($v, 0, ',', ' ');
		}

		return $query;
	}

	public function getWeeklyUsers(){
		$query = $this->db->query("SELECT
				COALESCE( COUNT( registered ), 0 ) AS count,
				DAYNAME( FROM_UNIXTIME( registered ) ) as dayname,
				DATE( FROM_UNIXTIME( registered ) ) as day
			FROM users
			WHERE 
				YEARWEEK( FROM_UNIXTIME( registered ) ) = YEARWEEK( NOW() )
			GROUP BY day;");
		$query = $query->result_array();
		
		return $query;
	}

	public function getMonthlyUsers(){
		$query = $this->db->query("SELECT
			COALESCE( COUNT( registered ), 0 ) AS count,
			DATE( FROM_UNIXTIME( registered ) ) as day
		FROM users
		WHERE 
			EXTRACT( YEAR_MONTH FROM FROM_UNIXTIME( registered ) ) = EXTRACT( YEAR_MONTH FROM NOW() )
		GROUP BY day;");
		$query = $query->result_array();
		
		return $query;
	}

	public function getMonthlyVotes(){
		$checked_query = $this->db->query("SELECT
				COALESCE( COUNT( date ), 0 ) AS count,
				DATE( FROM_UNIXTIME( date ) ) as day
			FROM contest_votes
			WHERE 
				EXTRACT( YEAR_MONTH FROM FROM_UNIXTIME( date ) ) = EXTRACT( YEAR_MONTH FROM NOW() )
				AND check_status = 1
			GROUP BY day;");
		$checked_query = $checked_query->result_array();
		$checked = [];
		foreach ($checked_query as $row) $checked[ $row['day'] ] = $row['count'];

		$unchecked_query = $this->db->query("SELECT
				COALESCE( COUNT( date ), 0 ) AS count,
				DATE( FROM_UNIXTIME( date ) ) as day
			FROM contest_votes
			WHERE 
				EXTRACT( YEAR_MONTH FROM FROM_UNIXTIME( date ) ) = EXTRACT( YEAR_MONTH FROM NOW() )
				AND check_status = 0
			GROUP BY day;");
		$unchecked_query = $unchecked_query->result_array();
		$unchecked = [];
		foreach ($unchecked_query as $row) $unchecked[ $row['day'] ] = $row['count'];

		$ignored_query = $this->db->query("SELECT
				COALESCE( COUNT( date ), 0 ) AS count,
				DATE( FROM_UNIXTIME( date ) ) as day
			FROM contest_votes
			WHERE 
				EXTRACT( YEAR_MONTH FROM FROM_UNIXTIME( date ) ) = EXTRACT( YEAR_MONTH FROM NOW() )
				AND check_status = 2
			GROUP BY day;");
		$ignored_query = $ignored_query->result_array();
		$ignored = [];
		foreach ($ignored_query as $row) $ignored[ $row['day'] ] = $row['count'];

		$votes = [
			'checked' => [],
			'unchecked' => [],
			'ignored' => [],
		];

		for ($i = 1; $i <= intval(date('d')); $i++) {
			if ( $i < 10 ) $i = '0'.$i;
	        $date = date("Y-m-".$i);
	        
	        if ( array_key_exists($date, $checked) ) {
	        	$votes['checked'][] = [
	        		'day' => $date,
	        		'count' => $checked[ $date ],
	        	];
	        }else{
	        	$votes['checked'][] = [
	        		'day' => $date,
	        		'count' => 0,
	        	];
	        }

	        if ( array_key_exists($date, $unchecked) ) {
	        	$votes['unchecked'][] = [
	        		'day' => $date,
	        		'count' => $unchecked[ $date ],
	        	];
	        }else{
	        	$votes['unchecked'][] = [
	        		'day' => $date,
	        		'count' => 0,
	        	];
	        }

	        if ( array_key_exists($date, $ignored) ) {
	        	$votes['ignored'][] = [
	        		'day' => $date,
	        		'count' => $ignored[ $date ],
	        	];
	        }else{
	        	$votes['ignored'][] = [
	        		'day' => $date,
	        		'count' => 0,
	        	];
	        }
	    }

		return $votes;
	}

	public function getUsersAge(){
		$query = $this->db->query("SELECT CASE WHEN `age` < 15 THEN '<15' WHEN `age` BETWEEN 15 and 20 THEN '15-20' WHEN `age` BETWEEN 21 and 30 THEN '21-30' WHEN `age` BETWEEN 31 and 40 THEN '31-40' WHEN `age` BETWEEN 41 and 50 THEN '41-50' WHEN `age` BETWEEN 51 and 60 THEN '51-60' WHEN `age` > 61 THEN '61<' END as rng, count(`id`) as count FROM ( SELECT id, TIMESTAMPDIFF(YEAR, CONCAT(age, '-01-01'), CURDATE()) AS age FROM additional_fields ) t GROUP BY rng ORDER BY `rng` DESC");
		
		$query = $query->result_array();
		
		foreach ($query as $k => $v) {
			if ( empty( $v['rng'] ) ) {
				$query[ $k ]['rng'] = "Aniqlanmagan";
			}
		}

		return $query;
	}

	public function getCities(){
		$ct =  [
    		['code' => 'uz-an', 'name' => ['Andijon viloyati', 'Андижанская область']],
    		['code' => 'uz-bu', 'name' => ['Buxoro viloyati', 'Бухарская область']],
    		['code' => 'uz-fa', 'name' => ['Fargʻona viloyati', 'Ферганская область']],
    		['code' => 'uz-ji', 'name' => ['Jizzax viloyati', 'Джизакская область']],
    		['code' => 'uz-kh', 'name' => ['Xorazm viloyati', 'Хорезмская область']],
    		['code' => 'uz-ng', 'name' => ['Namangan viloyati', 'Наманганская область']],
    		['code' => 'uz-nw', 'name' => ['Navoiy viloyati', 'Навоийская область']],
    		['code' => 'uz-qa', 'name' => ['Qashqadaryo viloyati', 'Кашкадарьинская область']],
    		['code' => 'uz-qr', 'name' => ['Qoraqalpogʻiston Respublikasi', 'Республика Каракалпакстан']],
    		['code' => 'uz-sa', 'name' => ['Samarqand viloyati', 'Самаркандская область']],
    		['code' => 'uz-si', 'name' => ['Sirdaryo viloyati', 'Сырдарьинская область']],
    		['code' => 'uz-su', 'name' => ['Surxondaryo viloyati', 'Сурхандарьинская область']],
    		['code' => 'uz-tk', 'name' => ['Toshkent shahri', 'г.Ташкент']],
    		['code' => 'uz-ta', 'name' => ['Toshkent viloyati', 'Ташкентская область']]
    	];

    	$tmp = [];

    	foreach ( $ct as $row ) {
    		$where_in = "'".implode("', '", $row['name'])."'";
    		$query = $this->db->query("SELECT (SELECT COUNT(totalcount) total FROM ( SELECT COUNT(*) totalcount FROM users INNER JOIN additional_fields ON additional_fields.user_id=users.id WHERE additional_fields.city IN({$where_in}) AND users.language = 'russian' GROUP BY chat_id ) src ) as russian, (SELECT COUNT(totalcount) total FROM ( SELECT COUNT(*) totalcount FROM users INNER JOIN additional_fields ON additional_fields.user_id=users.id WHERE additional_fields.city IN({$where_in}) AND users.language = 'uzbek' GROUP BY chat_id ) src ) as uzbek, (SELECT COUNT(totalcount) total FROM ( SELECT COUNT(*) totalcount FROM users INNER JOIN additional_fields ON additional_fields.user_id=users.id WHERE additional_fields.city IN({$where_in}) AND users.language IS NULL GROUP BY chat_id ) src ) as isnull;")->row_array();
			
			$tmp[$row['code']]['code'] = $row['code'];
			$tmp[$row['code']]['name'] = $row['name'][0];
			$tmp[$row['code']]['values'] = $query;
			$tmp[$row['code']]['all'] = $query['russian'] + $query['uzbek'] + $query['isnull'];
		}

		usort($tmp, function($a, $b) {
		    if($a['all']==$b['all']) return 0;
		    return $a['all'] < $b['all']?1:-1;
		});

		return $tmp;
	}
}

/* End of file Stats_model.php */
/* Location: ./application/models/client/Stats_model.php */