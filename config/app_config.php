<?php
	ini_set('xdebug.show_local_vars', 'On'); 
    ini_set('xdebug.collect_params', '4');
    ini_set('xdebug.var_display_max_depth', -1);
    ini_set('xdebug.var_display_max_children', -1);
    define('URL', 'http://miracle-number.codio.io:3000');
    $path = $_SERVER['DOCUMENT_ROOT'];
    define('INCURL', $path);
    define('ADMIN', 'alexbar137@gmail.com');
	date_default_timezone_set('Asia/Novosibirsk');
?>