<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_model extends CI_Model {
	public function load_index( $arr=[] ){
		$this->load->view('manage/index', $arr);
	}

	public function is_active( $module ){
		$config = $this->load_config( $module );
		return isset( $config['installed'] ) ? $config['installed'] : FALSE;
	}

	public function load_config( $module ){
		if ( file_exists( APPPATH . 'modules/' . $module . '/data/config.php' ) ) {
			return include( APPPATH . 'modules/' . $module . '/data/config.php' );
		}

		return FALSE;
	}

	public function save_config( $module, $data ){
		$data = "<?php\nreturn ".var_export( $data, true ).";\n?>";
		return file_put_contents( APPPATH . 'modules/' . $module . '/data/config.php', $data);
	}

	public function load_settings( $module, $defaults = [] ){
		if ( file_exists( APPPATH . 'modules/' . $module . '/data/settings.php' ) ) {
			return include( APPPATH . 'modules/' . $module . '/data/settings.php' );
		}

		return $defaults;
	}

	public function save_settings( $module, $data ){
		$data = "<?php\nreturn ".var_export( $data, true ).";\n?>";
		return file_put_contents( APPPATH . 'modules/' . $module . '/data/settings.php', $data);
	}

	public function add_route( $module, $route ){
		$routes = $this->get_routes();
		$routes[$module] = $route;
		$this->save_routes( $routes );
		return TRUE;
	}

	public function remove_route( $module ){
		$routes = $this->get_routes();
		if ( array_key_exists($module, $routes) ) {
			unset($routes[ $module ]);
		}
		$this->save_routes( $routes );
		return TRUE;
	}

	public function get_routes(){
		return include( APPPATH . 'config/routes_dynamic.php' );
	}

	public function save_routes( $data ){
		$data = "<?php\nreturn ".var_export( $data, true ).";\n?>";
		return file_put_contents( APPPATH . 'config/routes_dynamic.php', $data);
	}

	public function get_menus( $menu ){
		return include( APPPATH . 'config/'.$menu.'_menu.php' );
	}

	public function save_menus( $menu, $data ){
		$data = "<?php\nreturn ".var_export( $data, true ).";\n?>";
		return file_put_contents( APPPATH . 'config/'.$menu.'_menu.php', $data);
	}

	public function add_menus( $menu, $module, $items ){
		$menu_items = $this->get_menus( $menu );
		$menu_items[$module] = $items;
		$this->save_menus( $menu, $menu_items );
		return TRUE;
	}

	public function remove_menus( $menu, $module ){
		$menu_items = $this->get_menus( $menu );
		if ( array_key_exists($module, $menu_items) ) {
			unset($menu_items[ $module ]);
		}
		$this->save_menus( $menu, $menu_items );
		return TRUE;
	}
}

/* End of file System_model.php */
/* Location: ./application/models/System_model.php */