<?php

define('RICHRELATED_WPCONFIG', '');

function cc_get_wpconfig() {
	if (RICHRELATED_WPCONFIG == '') {
	    $d = 0;
	    while (!file_exists(str_repeat('../', $d).'wp-config.php'))
	        if (++$d > 99) exit;
	    $wpconfig = str_repeat('../', $d).'wp-config.php';
	    return $wpconfig;
    }
    else return RICHRELATED_WPCONFIG;
}

?>