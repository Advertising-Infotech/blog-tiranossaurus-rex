<?php
/**
 * Router script for PHP built-in server
 * Usage: php -S 0.0.0.0:54549 -t . router.php
 *
 * Serves static files directly, routes everything else through index.php
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

$doc_root = __DIR__;
$file_path = $doc_root . str_replace('/', DIRECTORY_SEPARATOR, $path);

// Serve existing static files directly
if ($path !== '/') {
    $real = realpath($file_path);
    if ($real && is_file($real) && !str_ends_with($path, '.php')) {
        return false;
    }
}

// Route through index.php for everything else
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
