<?php
define("DIR_NAME", "speeltuinzoeker");

if (gethostname() == 'MyPC') {
	define('BASE_PATH', "C:/xampp/htdocs/" . DIR_NAME . "/");
	define('BASE_URL', "http://localhost/" . DIR_NAME . "/");
	// DB
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_NAME', 'speeltuinzoeker');
} else {
	define('BASE_PATH', "/home/deb56875n2/domains/speeltuinzoeker.nl/public_html/");
	define('BASE_URL', "http://www.speeltuinzoeker.nl/");
	// DB
	define('DB_HOST', 'localhost');
	define('DB_USER', 'user');
	define('DB_PASS', 'pass');
	define('DB_NAME', 'speeltuinzoeker');
}

spl_autoload_register(function ($class) {
	$classFile = BASE_PATH . 'lib/' . strtolower($class) . '.class.php';
	if (file_exists($classFile)) {
		include_once $classFile;
	} else {
		echo "Kan " . $classFile . " niet laden<br>";
	}
});

define("MEDIA_PATH", BASE_PATH . "media/");
define("MEDIA_URL", BASE_URL . "media/");

define('SALT', "SaLt");

define('ADMIN_MAIL', "admin@test.nl");

define("MAX_NR_OF_PHOTOS", 3);
define("MAX_PHOTO_DIM", 600);

define("MAPS_API_KEY", "MyKey");