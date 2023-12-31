<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modulelang_model extends CI_Model {

	public function get_items(){
		$items = [];

		$modules_map = directory_map( APPPATH . 'modules', 1);
		if (($key = array_search('index.html', $modules_map)) !== FALSE) unset($modules_map[$key]);
		
		foreach ($modules_map as $module) {
			$module = rtrim($module, '/');

			if ( folder_exist( APPPATH . 'modules/' . $module . '/language/' ) ) {
				$languages_map = directory_map( APPPATH . 'modules/' . $module . '/language/', 2);
				if (($key = array_search('index.html', $languages_map)) !== FALSE) unset($languages_map[$key]);

				foreach ($languages_map as $k => $v) {
					$k = rtrim($k, '/');

					if ( !empty( $v ) ) {
						foreach ($v as $j => $l) {
							if( preg_match('/.+_lang\.php/', $l) ){
								$item = str_replace( '_lang.php', '', $l);
								
								ob_start();
									if (isset( $lang )) unset($lang);
									include( APPPATH . 'modules/' . $module . '/language/' . $k . '/' . $l );
									if ( isset( $lang ) ) {
										echo json_encode($lang);
									}else{
										echo "[]";
									}
								$output = ob_get_contents();
								ob_end_clean();
								$items[$module][ $k ][ $item ] = json_decode($output, TRUE);
							}		
						}
					} 
				}
			}
		}

		foreach ($modules_map as $module) {
			$module = rtrim($module, '/');
			if ( folder_exist( APPPATH . 'modules/' . $module . '/language/' ) ) {
				$languages_map = directory_map( APPPATH . 'modules/' . $module . '/language/', 2);
				if (($key = array_search('index.html', $languages_map)) !== FALSE) unset($languages_map[$key]);

				foreach ($languages_map as $k => $v) {
					$k = rtrim($k, '/');
					if ( $k == 'uzbek' ) continue;

					foreach ( $items[$module]['uzbek'] as $kk => $vv ) {
						if ( !array_key_exists( $k, $items[$module] ) ) $items[$module][$k] = [] ;
						if ( !array_key_exists( $kk, $items[$module][ $k ] ) ) $items[$module][ $k ][ $kk ] = [];

						foreach( $vv as $kkk => $vvv ){
							if ( !array_key_exists( $kkk, $items[$module][ $k ][ $kk ] ) ) {
								$items[$module][ $k ][ $kk ][ $kkk ] = '';
							}
						}
					}
				}
			}
		}

		return $items;
	}

	public function save_items(){
		$data = $this->input->post();

		foreach ($data as $k => $v) {
			if ( folder_exist( APPPATH . 'modules/' . $k ) ) {
				foreach ($v as $kk => $vv) {
					if ( folder_exist( APPPATH . 'modules/' . $k . '/language/' . $kk ) ) {
						foreach ($vv as $kkk => $vvv) {
							$buffer = "<?php" . PHP_EOL . PHP_EOL;
							foreach ($vvv as $kkkk => $vvvv) {
								$buffer .= '$lang[\''.addcslashes($kkkk, "'").'\'] = "'.addcslashes($vvvv, '\"').'";' . PHP_EOL;	
							}
							file_put_contents(APPPATH . 'modules/' . $k . '/language/' . $kk . '/' . $kkk . '_lang.php', $buffer);
						}
					}
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

/* End of file Modulelang_model.php */
/* Location: ./application/modules/lang/models/Modulelang_model.php */