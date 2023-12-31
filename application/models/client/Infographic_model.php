<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infographic_model extends CI_Model {
	private $votes;

	public function getvotes( $nomination=[], $member=[], $status=[] ){
		$where_in = "";

		if ( !empty( $nomination ) ) {
			$where_in .= " AND c.nomination in('".implode("', '", array_unique( $nomination ) )."')";
		}

		if ( !empty( $member ) ) {
			$where_in .= " AND c.member in('".implode("', '", array_unique( $member ) )."')";
		}

		if ( !empty( $status ) ) {
			$where_in .= " AND c.check_status in('".implode("', '", array_unique( $status ) )."')";
		}

		$this->votes = "SELECT c.chat_id FROM `contest_votes` c WHERE c.date > 0{$where_in} GROUP BY chat_id";
	}

	public function getUsers(){
		$query = $this->db->query("SELECT
			(SELECT COUNT(totalcount) total
		        FROM
		        (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE users.chat_id IN ({$this->votes})
		            GROUP BY users.chat_id
		        ) src
		    ) as all_users,
		    (SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					INNER JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE additional_fields.gender IN('🧑 Erkak', '🧑 Мужчина') AND users.chat_id IN ({$this->votes})
			        GROUP BY users.chat_id
			    ) src
			) as all_mans,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					INNER JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE additional_fields.gender IN('👩 Ayol', '👩 Женщина') AND users.chat_id IN ({$this->votes})
					GROUP BY users.chat_id
			    ) src
			) as all_womans,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					LEFT JOIN additional_fields ON additional_fields.user_id=users.id
					WHERE ( additional_fields.gender IS NULL OR additional_fields.gender = '' ) AND users.chat_id IN ({$this->votes})
					GROUP BY users.chat_id
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
					AND chat_id IN ({$this->votes})
			        GROUP BY chat_id
			    ) src
			) as all_uzbek,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE language = 'russian' 
					AND chat_id IN ({$this->votes})
					GROUP BY chat_id
			    ) src
			) as all_russian,
			(SELECT COUNT(totalcount) total
			    FROM
			    (
					SELECT COUNT(*) totalcount 
					FROM users
					WHERE ( language IS NULL OR language = '0' OR language = '' ) 
					AND chat_id IN ({$this->votes})
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

	public function getUsersAge(){
		$query = $this->db->query("SELECT
			CASE WHEN `age` < 15 THEN '<15' WHEN `age`
			BETWEEN 15 and 20 THEN '15-20' WHEN `age`
			BETWEEN 21 and 30 THEN '21-30' WHEN `age`
			BETWEEN 31 and 40 THEN '31-40' WHEN `age`
			BETWEEN 41 and 50 THEN '41-50' WHEN `age`
			BETWEEN 51 and 60 THEN '51-60' WHEN `age` > 61 
			THEN '61<' END as rng, count(`id`) as count
		FROM (
			SELECT additional_fields.id, TIMESTAMPDIFF(YEAR, CONCAT(additional_fields.age, '-01-01'), CURDATE()) AS age
			FROM users
			INNER JOIN additional_fields ON additional_fields.user_id=users.id
			WHERE users.chat_id IN ({$this->votes})
		    GROUP BY users.chat_id
		) t GROUP BY rng ORDER BY `rng` DESC");
		
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
    		$query = $this->db->query("SELECT 
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id
						WHERE additional_fields.city IN({$where_in}) AND users.language = 'russian' AND users.chat_id IN ({$this->votes})
						GROUP BY chat_id
					) src ) as russian, 
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id
						WHERE additional_fields.city IN({$where_in}) AND users.language = 'uzbek' AND users.chat_id IN ({$this->votes})
						GROUP BY chat_id
					) src ) as uzbek, 
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id 
						WHERE additional_fields.city IN({$where_in}) AND (  users.language IS NULL OR users.language = '' OR users.language = '0' ) AND users.chat_id IN ({$this->votes}) 
						GROUP BY chat_id
					) src ) as isnull;
			")->row_array();
			
			$tmp[$row['code']]['code'] = $row['code'];
			$tmp[$row['code']]['name'] = $row['name'][0];
			$tmp[$row['code']]['values'] = $query;
			$tmp[$row['code']]['all'] = $query['russian'] + $query['uzbek'] + $query['isnull'];
		}

		usort($tmp, function($a, $b) {
		    if($a['all']==$b['all']) return 0;
		    return $a['all'] < $b['all']?1:-1;
		});

		$query = $this->db->query("SELECT
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id
						WHERE ( additional_fields.city IS NULL OR additional_fields.city = '' OR additional_fields.city = '0' ) AND users.language = 'russian' AND users.chat_id IN ({$this->votes})
						GROUP BY chat_id
					) src ) as russian, 
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id
						WHERE ( additional_fields.city IS NULL OR additional_fields.city = '' OR additional_fields.city = '0' ) AND users.language = 'uzbek' AND users.chat_id IN ({$this->votes})
						GROUP BY chat_id
					) src ) as uzbek, 
				(SELECT COUNT(totalcount) total 
					FROM ( 
						SELECT COUNT(*) totalcount 
						FROM users 
						INNER JOIN additional_fields ON additional_fields.user_id=users.id 
						WHERE ( additional_fields.city IS NULL OR additional_fields.city = '' OR additional_fields.city = '0' ) AND (  users.language IS NULL OR users.language = '' OR users.language = '0' ) AND users.chat_id IN ({$this->votes}) 
						GROUP BY chat_id
					) src ) as isnull")->row_array();

		$tmp['isnull']['code'] = 'isnull';
		$tmp['isnull']['name'] = 'Noaniq';
		$tmp['isnull']['values'] = $query;
		$tmp['isnull']['all'] = $query['russian'] + $query['uzbek'] + $query['isnull'];

		return $tmp;
	}
}

/* End of file Infographic_model.php */
/* Location: ./application/models/client/Infographic_model.php */