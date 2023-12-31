<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Systemlang_model extends CI_Model {
	public function get_items(){
		$items = [];

		$languages_map = directory_map( APPPATH . 'language', 2);
		if (($key = array_search('index.html', $languages_map)) !== FALSE) unset($languages_map[$key]);

		foreach ($languages_map as $k => $v) {
			$k = rtrim($k, '/');

			if ( !empty( $v ) ) {
				foreach ($v as $j => $l) {
					if( preg_match('/.+_lang\.php/', $l) ){
						$item = str_replace( '_lang.php', '', $l);
						
						ob_start();
							if (isset( $lang )) unset($lang);
							include( APPPATH . 'language/' . $k . '/' . $l );
							if ( isset( $lang ) ) {
								echo json_encode($lang);
							}else{
								echo "[]";
							}
						$output = ob_get_contents();
						ob_end_clean();
						$items[ $k ][ $item ] = json_decode($output, TRUE);
					}		
				}
			} 
		}

		foreach ($languages_map as $k => $v) {
			$k = rtrim($k, '/');
			if ( $k == 'uzbek' ) continue;

			foreach ( $items['uzbek'] as $kk => $vv ) {
				if ( !array_key_exists( $kk, $items[ $k ] ) ) $items[ $k ][ $kk ] = [];

				foreach( $vv as $kkk => $vvv ){
					if ( !array_key_exists( $kkk, $items[ $k ][ $kk ] ) ) {
						$items[ $k ][ $kk ][ $kkk ] = '';
					}
				}
			}
		}

		return $items;
	}

	public function save_items(){
		$data = $this->input->post();

		foreach ($data as $k => $v) {
			if ( $k == 'uzbek' ) continue;

			if ( folder_exist( APPPATH . 'language/' . $k . '/' ) ) {
				foreach ($v as $kk => $vv) {
					$buffer = "<?php" . PHP_EOL . PHP_EOL;

					foreach ($vv as $kkk => $vvv) {
						$buffer .= '$lang[\''.addcslashes($kkk, "'").'\'] = "'.addcslashes($vvv, '\"').'";' . PHP_EOL;						
					}

					file_put_contents(APPPATH . 'language/' . $k . '/' . $kk . '_lang.php', $buffer);
				}
			}
		}
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [ lang('module_succesfully_saved') ],
                //'_callback' => 'function(){ setTimeout(() => { location.reload() }, 1500); }'
            ])
    	);
	}
}

/* End of file Systemlang_model.php */
/* Location: ./application/modules/lang/models/Systemlang_model.php */