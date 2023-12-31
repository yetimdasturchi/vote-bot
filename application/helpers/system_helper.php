<?php

function getDefaultLanguage( bool $info = FALSE ){
	$language = !empty( get_cookie('language') ) ? get_cookie('language') : config_item('language');

	if ( $info ) {
		$info_file = APPPATH . 'language' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . 'info.json';

		return array_merge( json_decode( @file_get_contents($info_file), TRUE ), ['language' => $language] );
	}

	return $language;
}

function getLanguagedata( $language ){
	$info_file = APPPATH . 'language' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . 'info.json';
	if ( file_exists( $info_file ) ) {
		return array_merge( json_decode( @file_get_contents($info_file), TRUE ), ['language' => $language] );
	}
	
	return FALSE;
}

function getLanguages( bool $incd = FALSE ) {
	$map = directory_map( APPPATH . 'language', 1);

	if (($key = array_search('index.html', $map)) !== FALSE) {
    	unset($map[$key]);
	}

	sort( $map );

	$languages = [];

	$default_language = getDefaultLanguage();

	foreach ($map as $k => $v) {
		$info_file = APPPATH . 'language' . DIRECTORY_SEPARATOR . $v . 'info.json';

		if (
			file_exists( $info_file ) &&
			( $incd ? TRUE : ( $default_language != rtrim($v, '/') ) )
		) {
			$languages[ rtrim($v, '/') ] = json_decode( @file_get_contents( $info_file ), TRUE );
		} 
	}

	return $languages;
}

function clear_phone($number) {
  return preg_replace('/\D/', '',  $number);
}

function validate_phone($number) {
  return  boolval( preg_match("/^998(90|91|93|94|95|97|98|99|33|88|50|77)[0-9]{7}$/", $number ) );
}

function format_phone($number) {
    return preg_replace( '/^(998)(90|91|93|94|95|97|98|99|33|88|50|77)([0-9]{3})([0-9]{2})([0-9]{2})$/', '+$1 ($2) $3-$4-$5', $number);
}

function remove_emojis($string){
    $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
    $string = preg_replace($regex_alphanumeric, '', $string);

    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $string = preg_replace($regex_symbols, '', $string);

    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $string = preg_replace($regex_emoticons, '', $string);

    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $string = preg_replace($regex_transport, '', $string);
    
    $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
    $string = preg_replace($regex_supplemental, '', $string);

    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $string = preg_replace($regex_misc, '', $string);

    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $string = preg_replace($regex_dingbats, '', $string);

    $regex_dingbats = '/[\x{1242B}]/u';
    $string = preg_replace($regex_dingbats, '', $string);

    $regex_dingbats = '/[\x{11CAD}]/u';
    $string = preg_replace($regex_dingbats, '', $string);

    return $string;
}

function get_prop_by_path( $source, $path ) {
	$keys = explode('.', $path);
	foreach($keys as $key) {
		if (!array_key_exists($key, $source)) {
			return NULL; 
		}
		$source = $source[$key];
	}
	return $source;
}

function set_prop_by_path( &$source, $path, $value, $append = false ) {
	$keys = explode('.', $path);
	foreach($keys as $key) {
		if (!array_key_exists($key, $source)) {
			return NULL; 
		}
		$source = &$source[$key];
	}
	if( is_array( $source ) ) {
		switch (true) {
			case $append == "append" || $append == "push":
				array_push( $source, $value );
				break;
				case $append == "prepend" || $append == "unshift":
				array_unshift( $source, $value );
				break;
			default:
				array_push( $source, $value );
		}
	} else {
		$source = $value;
	}
	return true;
}

function clear_cache($exts=[], $interval = 0 ){
	$path = config_item( 'cache_path' );
	if ( empty( $path ) ) $path = APPPATH . 'cache/';

	if ($handle = opendir( $path )) {
		while (false !== ($file = readdir($handle))) {
			
			$check_time = ( (int)$interval > 0 ) ? ( filectime($path . $file) < ( time() - (int)$interval ) ) : TRUE;

			if ( $check_time ) {
				if ( in_array( strtolower( pathinfo($path . $file, PATHINFO_EXTENSION) ), $exts) ) {
					@unlink( $path . $file );
				}
			}
		}
	}
}

function folder_exist( $folder ){
	$path = realpath($folder);

	if( $path !== FALSE AND is_dir( $path ) ) {
		return $path;
	}

	return FALSE;
}

function get_time_ago( $time ) {
    $time_difference = time() - $time;

    if( $time_difference < 1 ) { return '1 soniya oldin'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'yil',
                30 * 24 * 60 * 60       =>  'oy',
                24 * 60 * 60            =>  'kun',
                60 * 60                 =>  'soat',
                60                      =>  'daqiqa',
                1                       =>  'soniya'
    );

    foreach( $condition as $secs => $str ) {
        $d = $time_difference / $secs;

        if( $d >= 1 ) {
            $t = round( $d );
            return $t . ' ' . $str . ( $t > 1 ? '' : '' ) . ' avval';
        }
    }
}

function array_partition( $list, $p ) {
	shuffle( $list );

    $listlen = count( $list );
    
    $partlen = floor( $listlen / $p );
    $partrem = $listlen % $p;
    $partition = [];
    $mark = 0;
    
    for( $px = 0; $px < $p; $px ++ ) {
        $incr = ( $px < $partrem ) ? $partlen + 1 : $partlen;
        $partition[ $px ] = array_slice( $list, $mark, $incr );
        $mark += $incr;
    }

    return $partition;
}

function isValidYear( $input ) {
	
	if ( ! is_numeric( $input ) ) {
		return FALSE;
	}

	$input = (int) $input;
    
    return $input > 1900 && $input < 2100;
}