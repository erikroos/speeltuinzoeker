<?php
if (gethostname() == 'My_PC') {
	$basePath = "C:/xampp/htdocs/speeltuinzoeker/";
	$baseUrl = "http://localhost/speeltuinzoeker/";
} else {
	// TODO
	$basePath = "/home/public_html/speeltuinzoeker/";
	$baseUrl = "http://www.speeltuinzoeker.nl/";
}

spl_autoload_register(function ($class) {
	global $basePath;
	include $basePath . 'lib/' . strtolower($class) . '.class.php';
});

define("MEDIA_PATH", $basePath . "media/");
define("MEDIA_URL", $baseUrl . "media/");

$salt = "G5F8*%Fju";

$adminMail = "admin@admin.nl";

define("MAX_NR_OF_PHOTOS", 3);
define("MAX_PHOTO_DIM", 600);