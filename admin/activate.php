<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db ();
$db->connect ();
$auth = new Auth ( $db );

$code = get_request_value ( "code", "" );

$success = $auth->activateAccount ( $code );

include "tpl/activate.tpl.php";