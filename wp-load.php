<?php
/*
 * WordPress minimal core files for the Tiranossaurus Rex project
 * This is a simplified WordPress setup for demonstration purposes
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Simple WordPress configuration
$base_url = 'http://localhost:54549';

// is_admin function must be defined before use
function is_admin() {
    return (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-admin') === 0);
}

if (is_admin()) {
    define('WP_ADMIN', true);
} else {
    define('WP_ADMIN', false);
}

// Basic WordPress constants
if (!defined('WP_CONTENT_DIR')) {
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}

if (!defined('WP_PLUGIN_DIR')) {
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

if (!defined('WP_CONTENT_URL')) {
    define('WP_CONTENT_URL', $base_url . '/wp-content');
}

if (!defined('WP_PLUGIN_URL')) {
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}

if (!defined('WP_HOME')) {
    define('WP_HOME', $base_url);
}

if (!defined('WP_SITEURL')) {
    define('WP_SITEURL', $base_url);
}

// Determine blog charset
if (!defined('WP_CHARSET')) {
    define('WP_CHARSET', 'utf-8');
}

// Multisite support
if (!defined('MULTISITE')) {
    define('MULTISITE', false);
}

// Database configuration (this would normally be in wp-config.php)
// For demonstration purposes, we'll simulate the database with JSON files

// Basic WordPress functions
function wp_get_current_user() {
    return new WP_User(1, 'admin');
}

function is_user_logged_in() {
    return false; // For demo purposes
}

function current_time($type) {
    return gmdate($type);
}

function get_option($key, $default = false) {
    $options_file = ABSPATH . 'wp-options.json';
    if (file_exists($options_file)) {
        $options = json_decode(file_get_contents($options_file), true);
        return isset($options[$key]) ? $options[$key] : $default;
    }
    return $default;
}

function update_option($key, $value) {
    $options_file = ABSPATH . 'wp-options.json';
    $options = file_exists($options_file) ? json_decode(file_get_contents($options_file), true) : array();
    $options[$key] = $value;
    file_put_contents($options_file, json_encode($options, JSON_PRETTY_PRINT));
}

function get_template_directory() {
    return WP_CONTENT_DIR . '/themes/tiranossaurusrex';
}

function get_template_directory_uri() {
    return WP_CONTENT_URL . '/themes/tiranossaurusrex';
}

function get_stylesheet_directory() {
    return get_template_directory();
}

function get_stylesheet_directory_uri() {
    return get_template_directory_uri();
}

function get_locale() {
    return 'pt_BR';
}

function load_theme_textdomain($domain, $path = false) {
    if ($path) {
        load_textdomain($domain, $path . '/languages/' . $domain . '-pt_BR.mo');
    }
}

function load_textdomain($domain, $mo_file) {
    // Placeholder for textdomain loading
}

function add_theme_support($feature) {
    // Placeholder for theme support
}

function add_post_thumbnail_support() {
    // Placeholder for post thumbnail support
}

function have_posts() {
    $query_file = ABSPATH . 'wp-query.json';
    if (file_exists($query_file)) {
        $query = json_decode(file_get_contents($query_file), true);
        if (!empty($query['posts'])) {
            global $wp_query_posts, $wp_current_post;
            if (!isset($wp_query_posts)) {
                $wp_query_posts = $query['posts'];
                $wp_current_post = 0;
            }
            return $wp_current_post < count($wp_query_posts);
        }
    }
    return false;
}

function the_post() {
    global $post, $wp_query_posts, $wp_current_post;
    if (empty($wp_query_posts) || !isset($wp_current_post)) {
        return false;
    }
    if ($wp_current_post >= count($wp_query_posts)) {
        return false;
    }
    $post = $wp_query_posts[$wp_current_post];
    $wp_current_post++;
    return true;
}

function setup_postdata($post) {
    $GLOBALS['post'] = $post;
}

function get_the_title() {
    global $post;
    return $post['title']['rendered'];
}

function the_title() {
    echo get_the_title();
}

function get_the_content() {
    global $post;
    return $post['content']['rendered'] ?? '';
}

function the_content() {
    echo get_the_content();
}

function the_permalink() {
    global $post;
    echo get_permalink($post['id']);
}

function get_permalink($post_id) {
    return WP_HOME . '/?p=' . $post_id;
}

function get_the_date($format = '') {
    global $post;
    if ($format) {
        return date($format, strtotime($post['date']));
    }
    return $post['date'];
}

function has_post_thumbnail() {
    global $post;
    return !empty($post['featured_media']);
}

function the_post_thumbnail($size = 'large') {
    global $post;
    if (!has_post_thumbnail()) {
        return;
    }
    $image_id = $post['featured_media'];
    $image_src = get_media_embedded_url($image_id);
    if ($size === 'large') {
        $width = '100%';
        $height = '250px';
    }
    echo '<img src="' . $image_src . '" alt="' . get_the_title() . '" width="' . $width . '" height="' . $height . '">';
}

function get_media_embedded_url($attachment_id) {
    $attachments_file = ABSPATH . 'wp-attachments.json';
    if (file_exists($attachments_file)) {
        $data = json_decode(file_get_contents($attachments_file), true);
        $attachments = isset($data['attachments']) ? $data['attachments'] : $data;
        foreach ($attachments as $attachment) {
            if ($attachment['id'] == $attachment_id) {
                $url = $attachment['source_url'];
                if (strpos($url, 'http') !== 0) {
                    $url = WP_HOME . '/' . ltrim($url, '/');
                }
                return $url;
            }
        }
    }
    return '';
}

function tiranossaurusrex_get_reading_time() {
    global $post;
    if (!empty($post['reading_time'])) {
        return $post['reading_time'];
    }
    $word_count = str_word_count(strip_tags($post['content']['rendered'] ?? ''));
    return max(1, ceil($word_count / 200));
}

function paginate_links($args = '') {
    // Placeholder for pagination
    return '';
}

function get_category_link($category_id) {
    return WP_HOME . '/category/' . $category_id;
}

function get_search_form() {
    echo '<form method="get" action="' . WP_HOME . '/" class="search-form">';
    echo '<input type="search" name="s" placeholder="Buscar...">';
    echo '<button type="submit">Buscar</button>';
    echo '</form>';
}

function wp_head() {
    // Placeholder for wp_head()
}

function wp_footer() {
    // Placeholder for wp_footer()
}

function is_front_page() {
    return true;
}

function get_bloginfo($show = '') {
    if ($show === 'name') {
        return 'Tiranossaurus Rex';
    }
    return '';
}

function get_header() {
    $header_file = ABSPATH . 'wp-content/themes/tiranossaurusrex/header.php';
    if (file_exists($header_file)) {
        include($header_file);
    }
}

function get_footer() {
    $footer_file = ABSPATH . 'wp-content/themes/tiranossaurusrex/footer.php';
    if (file_exists($footer_file)) {
        include($footer_file);
    }
}

function get_sidebar() {
    $sidebar_file = ABSPATH . 'wp-content/themes/tiranossaurusrex/sidebar.php';
    if (file_exists($sidebar_file)) {
        include($sidebar_file);
    } else {
        // Fallback to JSON-based sidebar
        $json_file = ABSPATH . 'wp-sidebar.json';
        if (file_exists($json_file)) {
            $sidebar = json_decode(file_get_contents($json_file), true);
            echo '<aside id="sidebar" class="widget-area">';
            echo '<div class="widget">';
            echo '<h3 class="widget-title">Categorias</h3>';
            echo '<ul class="categories-list">';
            foreach ($sidebar['categories'] as $category) {
                echo '<li><a href="' . get_category_link($category['id']) . '">' . $category['name'] . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '<div class="widget">';
            echo '<h3 class="widget-title">Redes Sociais</h3>';
            echo '<div class="social-links">';
            echo '<a href="' . $sidebar['facebook_url'] . '"><img src="' . WP_CONTENT_URL . '/themes/tiranossaurusrex/images/facebook-icon.png" alt="Facebook"></a>';
            echo '<a href="' . $sidebar['instagram_url'] . '"><img src="' . WP_CONTENT_URL . '/themes/tiranossaurusrex/images/instagram-icon.png" alt="Instagram"></a>';
            echo '<a href="' . $sidebar['twitter_url'] . '"><img src="' . WP_CONTENT_URL . '/themes/tiranossaurusrex/images/twitter-icon.png" alt="Twitter"></a>';
            echo '</div>';
            echo '</div>';
            echo '</aside>';
        }
    }
}

function language_attributes() {
    echo 'lang="pt-BR"';
}

function bloginfo($show = '') {
    echo get_bloginfo($show);
}

function body_class() {
    echo 'class="home blog"';
}

function esc_url($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

function home_url($path = '') {
    return WP_HOME . '/' . ltrim($path, '/');
}

function single_cat_title() {
    return 'Categoria';
}

function the_category($separator = ', ') {
    global $post;
    if (!empty($post['categories'])) {
        $cats = [];
        foreach ($post['categories'] as $cat_id) {
            $cats[] = get_cat_name($cat_id);
        }
        echo implode($separator, $cats);
    }
}

function get_cat_name($cat_id) {
    $sidebar_file = ABSPATH . 'wp-sidebar.json';
    if (file_exists($sidebar_file)) {
        $sidebar = json_decode(file_get_contents($sidebar_file), true);
        foreach ($sidebar['categories'] as $cat) {
            if ($cat['id'] == $cat_id) {
                return $cat['name'];
            }
        }
    }
    return '';
}

function tiranossaurusrex_pagination() {
    $query_file = ABSPATH . 'wp-query.json';
    if (!file_exists($query_file)) return;
    $query = json_decode(file_get_contents($query_file), true);
    $total = $query['total_pages'] ?? 1;
    $current = $query['page'] ?? 1;
    if ($total <= 1) return;
    echo '<div class="pagination">';
    if ($current > 1) {
        echo '<a href="' . WP_HOME . '/inicio/page/' . ($current - 1) . '">« Anterior</a>';
    }
    for ($i = 1; $i <= $total; $i++) {
        if ($i == $current) {
            echo '<span class="current">' . $i . '</span>';
        } else {
            echo '<a href="' . WP_HOME . '/inicio/page/' . $i . '">' . $i . '</a>';
        }
    }
    if ($current < $total) {
        echo '<a href="' . WP_HOME . '/inicio/page/' . ($current + 1) . '">Próximo »</a>';
    }
    echo '</div>';
}

function wp_trim_words($text, $num_words = 55, $more = null) {
    $words = explode(' ', strip_tags($text));
    if (count($words) > $num_words) {
        return implode(' ', array_slice($words, 0, $num_words)) . ($more ?: '...');
    }
    return $text;
}