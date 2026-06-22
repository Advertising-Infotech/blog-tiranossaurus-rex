<?php
/*
 * WordPress Application Entry Point
 * This file serves as the main entry point for the WordPress application
 */

// Load WordPress core
require_once('wp-load.php');

// Start output buffering
ob_start();

// Handle WordPress routing
$uri = $_SERVER['REQUEST_URI'];

// Determine the query type
if (defined('WP_ADMIN') && WP_ADMIN) {
    // Admin functionality (not implemented in this minimal version)
    include('wp-admin/index.php');
} else {
    // Frontend routing
    if (empty($uri) || $uri === '/') {
        // Homepage - show posts
        include('wp-content/themes/tiranossaurusrex/index.php');
    } elseif (preg_match('/^\/post\/(\d+)$/', $uri, $matches)) {
        // Single post
        include('wp-content/themes/tiranossaurusrex/single.php');
    } elseif (preg_match('/^\/category\/([^\/]+)$/', $uri, $matches)) {
        // Category page
        include('wp-content/themes/tiranossaurusrex/category.php');
    } else {
        // 404 page
        include('wp-content/themes/tiranossaurusrex/404.php');
    }
}

// Output the content
ob_end_flush();
