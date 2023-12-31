<?php

function check_tasix( $ip ){
    $range = @file_get_contents(APPPATH . 'database/tasix.json');
    $range = json_decode($range, TRUE);
    if( !is_array( $range ) ) return false;
    ksort( $range );
    $ip2long = ip2long( $ip );

    if( $ip2long !== false ) {
        foreach( $range as $start => $end ) {
            $end = ip2long( $end );
            $start = ip2long( $start );
            $is_key = ( $start === false );

            if( $end === false ) continue;

            if(is_numeric( $start ) && $is_key && $end === $ip2long ) {
                return true;
            }else{
                if( !$is_key && $ip2long >= $start && $ip2long <= $end ) {
                    return true;
                }
            }
        }
    }

    return false;
}
