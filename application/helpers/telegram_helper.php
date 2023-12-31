<?php

function get_file_id( $file, $filename, $chat_id ){
	$url = isset( $GLOBALS['system_config']['telegram_api_url'] ) ? $GLOBALS['system_config']['telegram_api_url']  : "https://api.telegram.org";
	$token = isset( $GLOBALS['system_config']['bot_token'] ) ? $GLOBALS['system_config']['bot_token']  : "";

	$method = getMethod( $file, $filename );

	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . "/bot" . $token . $method['method'] );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
    $cFile = new CURLFile($file, $finfo);
    $cFile->setPostFilename( $filename );

    curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($method['post_data'], [
        $method['field'] => $cFile,
        'chat_id' => $chat_id
    ]));

    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
		$result = FALSE;
	}
	curl_close($ch);

	if ( $result ) {
		$result = json_decode( $result, TRUE );
		
		if ( $result['ok'] ) {
			if ( $method['field'] == 'photo' ) {
				$photo = end( $result['result']['photo'] );
				return ['type' => 'photo', 'file' => $photo['file_id'] ];
			}else{
				return ['type' => $method['field'], 'file' => $result['result'][ $method['field'] ]['file_id'] ];
			}
		}
	}

	return FALSE;
}

function getMethod( $file, $filename ){
	$ci =& get_instance();
	$ci->load->library('MyGetID3');
	$getID3 = new getID3;

	$ext = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );
	
	switch ($ext) {
		case 'jpg':
		case 'jpeg':
		case 'png':
			$method = '/sendPhoto';
			$field = 'photo';
			$post_data = [];
		break;

		case 'mp3':
		case 'm4a':
			$method = '/sendAudio';
			$field = 'audio';

			$filedata = $getID3->analyze( $file );
			$duration = !empty( $filedata['playtime_seconds'] ) ? $filedata['playtime_seconds'] : null;
			$post_data = [ 'duration' => $duration ];
		break;

		case 'mp4':
			$method = '/sendVideo';
			$field = 'video';

			$filedata = $getID3->analyze( $file );

			$width = isset( $filedata['video']['resolution_x'] ) ? $filedata['video']['resolution_x'] : null;
            $height = isset( $filedata['video']['resolution_y'] ) ? $filedata['video']['resolution_y'] : null;
			$duration = !empty( $filedata['playtime_seconds'] ) ? $filedata['playtime_seconds'] : null;

			$duration = !empty( $filedata['playtime_seconds'] ) ? $filedata['playtime_seconds'] : null;
			$post_data = [ 'duration' => $duration, 'width' => $width, 'height' => $height, 'supports_streaming' => 'true' ];
		break;

		case 'gif':
			$method = '/sendAnimation';
			$field = 'animation';
			$post_data = [];
		break;

		case 'ogg':
			$method = '/sendVoice';
			$field = 'voice';

			$filedata = $getID3->analyze( $file );
			$duration = !empty( $filedata['playtime_seconds'] ) ? $filedata['playtime_seconds'] : null;
			$post_data = [ 'duration' => $duration ];
		break;
		
		default:
			$method = '/sendDocument';
			$field = 'document';
			$post_data = [];
		break;
	}

	return [ 'method' => $method, 'field' => $field, 'post_data' => $post_data ];
}