<?php
    session_start();
    require_once 'libs/Bootstrap.php';
    require_once 'libs/Controller.php';
    require_once 'libs/View.php';
    require_once 'libs/Model.php';
    require_once 'config/app_config.php';
	require_once 'config/db_config.php';

    $app = new Bootstrap();
?>