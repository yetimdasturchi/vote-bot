<?php

function build_navbar_menu( $menu_items = [] ){
	$CI =& get_instance();

	if ( empty( $menu_items ) ) $menu_items = include ( APPPATH . 'config/navbar_menu.php' );

	if ( !empty( $menu_items ) ) {
		$menu = "";
		foreach($menu_items as $k => $module) {
			$CI->lang->load_module( $k, 'navbar' );
	       	foreach ($module as $item) {
	       		if ( !empty( $item['model'] ) ) {
	       			if ( $CI->load->is_module_exists( $k, $item['model'] ) ) {
	       				$CI->load->module_model($k, $item['model']);
	       				$model_name = 'module_' . $item['model'];
	       				if ( property_exists($CI->$model_name, 'module_name') ) {
	       					$CI->$model_name->module_name = $k;
	       				}
	       				$menu .= $CI->$model_name->parse();
	       			}

	       			continue;
	       		}

	       		$menu .= "<div class=\"dropdown ". ( empty( $item['mobile'] ) ? 'd-none ' : '' ) ."d-lg-inline-block ms-1\">";
	        	$menu .= "<button type=\"button\" class=\"btn header-item noti-icon waves-effect\"" . ( isset( $item['location'] ) ? 'data-location="'.$item['location'].'"' : '' ) . ( isset($item['dropdown']) ? ' data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '' ) .">";
	        	if ( isset( $item['icon'] ) ) $menu .= "<i class=\"{$item['icon']}\"></i>";
	        	if ( isset( $item['text'] ) ) $menu .= "<span class=\"d-none d-xl-inline-block ms-1\">".lang($item['text'])."</span>";
	        	$menu .= "</button>";
	        	if ( isset( $item['dropdown'] ) ) {
	        		$menu .= "<div class=\"dropdown-menu dropdown-menu-end\">";
	        		foreach ($item['dropdown'] as $dropdown) {
	        			$menu .= "<a class=\"dropdown-item\"".( isset( $dropdown['location'] ) ? ' href="'.$dropdown['location'].'"' : '' ).">";
	        			
	        			if ( isset( $dropdown['icon'] ) ) {
	        				$menu .= "<i class=\"{$dropdown['icon']} font-size-16 align-middle me-1\"></i>";
	        			}

	        			if ( isset( $dropdown['text'] ) ) {
	        				$menu .= "<span>".lang($dropdown['text'])."</span>";
	        			}

	        			$menu .= "</a>";
	        		}
	        		$menu .= "</div>";
	        	}
	        	$menu .= "</div>";
	       	}
	    }
	    
	    return $menu;
	}

	return "";
}

function build_sidebar_menu( $menu_items = [] ){
	if ( empty( $menu_items ) ) $menu_items = include ( APPPATH . 'config/sidebar_menu.php' );
	$CI =& get_instance();

	if ( $menu_items ) {
		$menu = "";

		foreach($menu_items as $module => $items) {
			$CI->lang->load_module( $module, 'sidebar' );

			foreach ($items as $k => $item) {
				if ( $k == 'title' ) $menu .= "<li class=\"menu-title\">{$item}</li>";

				if ( is_array( $item ) ) {
					if ( !empty( $item['model'] ) ) {
		       			if ( $CI->load->is_module_exists( $module, $item['model'] ) ) {
		       				$CI->load->module_model($module, $item['model']);
		       				$model_name = 'module_' . $item['model'];
		       				if ( property_exists($CI->$model_name, 'module_name') ) {
		       					$CI->$model_name->module_name = $module;
		       				}
		       				$menu .= $CI->$model_name->parse();
		       			}

		       			continue;
		       		}

					$menu .= "<li>";
					if ( isset( $item['children'] ) ) {
						$menu .= "<a href=\"javascript: void(0);\" class=\"has-arrow waves-effect\">";
					}else{
						$menu .= "<a ".( isset( $item['location'] ) ? 'href="'.$item['location'].'" ' : '' )."class=\"waves-effect\">";
					}
					if ( isset( $item['icon'] ) ) $menu .= "<i class=\"{$item['icon']}\"></i>";
					if ( isset( $item['text'] ) ) $menu .= "<span>{$item['text']}</span>";
					$menu .= "</a>";
					
					if ( isset( $item['children'] ) ) {
						$menu .= "<ul class=\"sub-menu\" aria-expanded=\"true\">";
						foreach ($item['children'] as $children) {
							$menu .= "<li>";
							if ( isset( $children['children'] ) ) {
								$menu .= "<a href=\"javascript: void(0);\" class=\"has-arrow\">";
							}else{
								$menu .= "<a".( isset( $children['location'] ) ? ' href="'.$children['location'].'" ' : '' ).">";
							}
							if ( isset( $children['icon'] ) ) $menu .= "<i class=\"{$children['icon']}\"></i>";
							if ( isset( $children['text'] ) ) $menu .= "<span>{$children['text']}</span>";
							$menu .= "</a>";

							if ( isset( $children['children'] ) ) {
								$menu .= "<ul class=\"sub-menu\" aria-expanded=\"true\">";
								foreach ($children['children'] as $subchildren) {
									$menu .= "<li>";
									$menu .= "<a".( isset( $subchildren['location'] ) ? ' href="'.$subchildren['location'].'" ' : '' ).">";
									if ( isset( $subchildren['icon'] ) ) $menu .= "<i class=\"{$subchildren['icon']}\"></i>";
									if ( isset( $subchildren['text'] ) ) $menu .= "<span>{$subchildren['text']}</span>";
									$menu .= "</a>";
									$menu .= "</li>";
								}
								$menu .= "</ul>";
							}

							$menu .= "</li>";
						}
						$menu .= "</ul>";
					}

					$menu .= "</li>";
				}

			}
		}

		return $menu;
	}

	return '';
}