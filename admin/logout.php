<?php
require_once "../cfg/config.php";

$auth = new Auth();
$auth->logout();

header("Location: ../index.php");