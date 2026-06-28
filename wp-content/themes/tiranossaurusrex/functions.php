<?php
/*
 * Theme: Tiranossaurus Rex - Perfect Clone
 * Description: Exact replica of trexhacker.wixsite.com/tiranossaurusrex
 * Version: 1.0.0
 */

function tiranossaurusrex_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_post_thumbnail_support();
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form', 'gallery', 'caption', 'comment-form', 'comment-list',
    ));
}

add_action('after_setup_theme', 'tiranossaurusrex_theme_setup');

function tiranossaurusrex_enqueue_scripts() {
    wp_enqueue_style('tiranossaurusrex-style', get_template_directory_uri() . '/style.css', [], '2.0.1');
    wp_enqueue_script('tiranossaurusrex-main', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('tiranossaurusrex-api', get_template_directory_uri() . '/js/api-fallback.js', array(), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'tiranossaurusrex_enqueue_scripts');

function tiranossaurusrex_widgets_init() {
    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar-1',
        'description' => 'Widget area for the sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'tiranossaurusrex_widgets_init');

function tiranossaurusrex_custom_excerpt_length($length) {
    return 30;
}

add_filter('excerpt_length', 'tiranossaurusrex_custom_excerpt_length');

