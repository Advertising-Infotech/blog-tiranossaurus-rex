<?php
require_once __DIR__ . '/wp-load.php';

$ABSPATH = defined('ABSPATH') ? ABSPATH : __DIR__ . '/';
$theme_dir = $ABSPATH . 'wp-content/themes/tiranossaurusrex/';

ob_start();

$uri = $_SERVER['REQUEST_URI'];

if (defined('WP_ADMIN') && WP_ADMIN) {
    include $ABSPATH . 'wp-admin/index.php';
} else {
    if (empty($uri) || $uri === '/') {
        include $theme_dir . 'index.php';
    } elseif (preg_match('/^\/post\/(\d+)$/', $uri, $matches)) {
        include $theme_dir . 'single.php';
    } elseif (preg_match('/^\/category\/([^\/]+)$/', $uri, $matches)) {
        include $theme_dir . 'category.php';
    } else {
        include $theme_dir . '404.php';
    }
}

ob_end_flush();
