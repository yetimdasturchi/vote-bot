<?php

function command_hash( $user_command, $language ){
	return md5( remove_emojis( $user_command ) ) . '_' . $language . '.command';
}