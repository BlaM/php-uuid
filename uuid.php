<?php

// When using UUID_MODE_TIMED, UUID_MODE_TYPED or UUID_MODE_USE_REMOTE_ADDR
// consider this as being a "controlled truly random".
// RFC 4122
define ('UUID_MODE_RANDOM',             0);
define ('UUID_MODE_TIMED',              1);
define ('UUID_MODE_TYPED',              2);
define ('UUID_MODE_USE_REMOTE_ADDR',    4);

function uuid($mode = UUID_MODE_RANDOM, $type = 0, $sep = '-') {
    if ($mode & UUID_MODE_TIMED) {
        list($usec, $sec) = explode(" ", microtime());
        $time_low = $usec * 0xFFFF;
        $time_mid = $sec & 0xFFFF;
        $time_hi = ($sec >> 16) & 0xFFFF;
    } else {
        $time_low = mt_rand( 0, 0xffff );
        $time_mid = mt_rand( 0, 0xffff );
        $time_hi = mt_rand( 0, 0xffff );
    }

    if ($mode & UUID_MODE_TYPED) {
        $time_low = ($time_low & 0xFF00) | ($type & 0xFF);
    }

    if ($mode & UUID_MODE_USE_REMOTE_ADDR) {
        $unique1 = ip2val($_SERVER[REMOTE_ADDR]);
        $unique2 = $unique1 & 0xFFFF;
        $unique1 = ($unique1 >> 16) & 0xFFFF;
    } else {
        $unique1 = mt_rand( 0, 0xffff );
        $unique2 = mt_rand( 0, 0xffff );
    }

    return sprintf( '%04x%04x' . $sep . '%04x' . $sep . '%04x' . $sep . '%04x' . $sep . '%04x%04x%04x',
           $time_hi, $time_mid, $time_low,
           mt_rand( 0, 0x0fff ) | 0x4000,
           mt_rand( 0, 0x3fff ) | 0x8000,
           $unique1, $unique2, mt_rand( 0, 0xffff ) );
}

function str2uuid($input) {
    $buffer = sha1($input);

    return substr($buffer, 0, 8) . '-' . substr($buffer, 8, 4) . '-4' . substr($buffer, 12, 3) .
           '-8' . substr($buffer, 16, 3) . '-' .
           substr($buffer, 20, 12);
}

function is_uuid($input) {
    return !!(preg_match("~^(\{){0,1}[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}(\}){0,1}$~", $input));
}
