<?php
// Router voor de PHP built-in server: bootst de .htaccess rewrites na.
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Admin-sectie: bootst `auto_prepend_file "./inc/auth.php"` uit admin/.htaccess na.
if (preg_match('#^/admin(/.*)?$#', $uri)) {
    $target = ($uri === '/admin' || $uri === '/admin/') ? '/admin/index.php' : $uri;
    $targetFile = __DIR__ . $target;
    // Niet-PHP bestanden (assets) direct serveren als ze bestaan.
    if (file_exists($targetFile) && !is_dir($targetFile) && substr($target, -4) !== '.php') {
        return false;
    }
    if (!file_exists($targetFile) || is_dir($targetFile)) {
        return false; // 404 door de server laten afhandelen
    }
    chdir(dirname($targetFile));
    $_SERVER['SCRIPT_NAME'] = $target;
    require __DIR__ . '/admin/inc/auth.php';
    require $targetFile;
    return true;
}

// Bestaand bestand? Laat de server het direct serveren.
$file = __DIR__ . $uri;
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

// Rewrite-regels uit .htaccess
if (preg_match('#^/speeltuinen/(.+)$#', $uri, $m)) {
    $_GET['speeltuin'] = $m[1];
    $_REQUEST['speeltuin'] = $m[1];
    require __DIR__ . '/detail.php';
    return true;
}

$map = [
    '/over'     => '/about.php',
    '/contact'  => '/contact.php',
    '/atoz'     => '/atoz.php',
    '/meedoen'  => '/join.php',
    '/privacy'  => '/privacy.php',
];
if (isset($map[$uri])) {
    require __DIR__ . $map[$uri];
    return true;
}

return false;
