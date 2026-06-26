<?php
/*
 * WordPress minimal core files for the Tiranossaurus Rex project
 * This is a simplified WordPress setup for demonstration purposes
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Simple WordPress configuration — auto-detect host
$detected_host = 'localhost';
$detected_port = '54549';
if (isset($_SERVER['HTTP_HOST'])) {
    $detected_host = $_SERVER['HTTP_HOST'];
} elseif (isset($_SERVER['SERVER_ADDR'])) {
    $detected_host = $_SERVER['SERVER_ADDR'];
}
$base_url = 'http://' . $detected_host;

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

function get_post_by_id($post_id) {
    $query_file = ABSPATH . 'wp-query.json';
    if (!file_exists($query_file)) return null;
    $query = json_decode(file_get_contents($query_file), true);
    if (!empty($query['posts'])) {
        foreach ($query['posts'] as $p) {
            if ($p['id'] == $post_id) return $p;
        }
    }
    return null;
}

function have_posts() {
    $query_file = ABSPATH . 'wp-query.json';
    if (file_exists($query_file)) {
        $query = json_decode(file_get_contents($query_file), true);
        if (!empty($query['posts'])) {
            global $wp_query_posts, $wp_current_post, $wp_total_posts, $wp_total_pages, $wp_current_page;
            if (!isset($wp_query_posts)) {
                $all_posts = $query['posts'];
                $wp_total_posts = count($all_posts);
                $wp_query_posts = array_slice($all_posts, 0, 27);
                $wp_current_post = 0;
                $wp_total_pages = $query['total_pages'] ?? 1;
                $wp_current_page = $query['page'] ?? 1;
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

/* === Action Hooks System === */
$wp_actions = [];
$wp_filters = [];

function add_action($hook, $callback, $priority = 10) {
    global $wp_actions;
    $wp_actions[$hook][] = ['callback' => $callback, 'priority' => $priority];
}

function do_action($hook, ...$args) {
    global $wp_actions;
    if (!empty($wp_actions[$hook])) {
        usort($wp_actions[$hook], fn($a, $b) => $a['priority'] - $b['priority']);
        foreach ($wp_actions[$hook] as $action) {
            call_user_func_array($action['callback'], $args);
        }
    }
}

function add_filter($hook, $callback, $priority = 10) {
    global $wp_filters;
    $wp_filters[$hook][] = ['callback' => $callback, 'priority' => $priority];
}

function apply_filters($hook, $value, ...$args) {
    global $wp_filters;
    if (!empty($wp_filters[$hook])) {
        usort($wp_filters[$hook], fn($a, $b) => $a['priority'] - $b['priority']);
        foreach ($wp_filters[$hook] as $filter) {
            $value = call_user_func_array($filter['callback'], array_merge([$value], $args));
        }
    }
    return $value;
}

/* === Asset Enqueue System === */
$wp_styles = [];
$wp_scripts = [];

function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
    global $wp_styles;
    $wp_styles[$handle] = [
        'src' => $src,
        'ver' => $ver ?: false,
        'media' => $media,
    ];
}

function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = false) {
    global $wp_scripts;
    $wp_scripts[$handle] = [
        'src' => $src,
        'ver' => $ver ?: false,
        'in_footer' => $in_footer,
    ];
}

function wp_print_styles() {
    global $wp_styles;
    foreach ($wp_styles as $handle => $style) {
        $src = $style['src'];
        $ver = $style['ver'] ? '?ver=' . $style['ver'] : '';
        echo '<link rel="stylesheet" id="' . esc_attr($handle) . '-css" href="' . esc_url($src) . $ver . '" media="' . esc_attr($style['media']) . '">' . "\n";
    }
}

function wp_print_footer_scripts() {
    global $wp_scripts;
    foreach ($wp_scripts as $handle => $script) {
        if ($script['in_footer']) {
            $src = $script['src'];
            $ver = $script['ver'] ? '?ver=' . $script['ver'] : '';
            echo '<script id="' . esc_attr($handle) . '-js" src="' . esc_url($src) . $ver . '"></script>' . "\n";
        }
    }
}

function wp_head() {
    do_action('wp_enqueue_scripts');
    wp_print_styles();
}

function wp_footer() {
    wp_print_footer_scripts();
}

function is_front_page() {
    return true;
}

function get_bloginfo($show = '') {
    if ($show === 'name') {
        return 'Tiranossaurus Rex';
    }
    if ($show === 'description') {
        return 'Blog de Atualidades';
    }
    if ($show === 'charset') {
        return 'UTF-8';
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

function esc_attr($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
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

function rewind_posts() {
    global $wp_query_posts, $wp_current_post;
    $wp_current_post = 0;
}

function locate_template($template_name) {
    $path = ABSPATH . 'wp-content/themes/tiranossaurusrex/' . $template_name;
    if (file_exists($path)) {
        return $path;
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

/* ============================================================
 * API Key Infrastructure — preparação para backend externo
 * ============================================================
 * Estas funções permitem que serviços externos (n8n, OpenClaw,
 * Hermes Agent, etc.) gerenciem o site via REST API.
 * O sistema de chaveamento será expandido no backend.
 */

function trex_generate_api_key() {
    return bin2hex(random_bytes(32));
}

function trex_validate_api_key($key) {
    $keys = get_option('trex_api_keys', []);
    if (!is_array($keys)) $keys = [];
    return in_array($key, $keys);
}

function trex_register_api_key($key, $label = '') {
    $keys = get_option('trex_api_keys', []);
    if (!is_array($keys)) $keys = [];
    $keys[] = ['key' => $key, 'label' => $label, 'created' => current_time('c')];
    update_option('trex_api_keys', $keys);
    return $key;
}

function trex_get_posts_json($page = 1, $per_page = 10) {
    $query_file = ABSPATH . 'wp-query.json';
    if (!file_exists($query_file)) return json_encode(['error' => 'No posts found']);
    $data = json_decode(file_get_contents($query_file), true);
    $posts = $data['posts'] ?? [];
    $total = count($posts);
    $offset = ($page - 1) * $per_page;
    $page_posts = array_slice($posts, $offset, $per_page);
    $default_img = WP_CONTENT_URL . '/themes/tiranossaurusrex/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg';
    foreach ($page_posts as &$p) {
        $img = '';
        if (!empty($p['featured_media'])) {
            $img = get_media_embedded_url($p['featured_media']);
        }
        $p['featured_image_url'] = $img ?: $default_img;
        $p['excerpt'] = wp_trim_words(strip_tags($p['content']['rendered'] ?? ''), 18);
    }
    unset($p);
    return json_encode([
        'posts' => $page_posts,
        'total' => $total,
        'page' => $page,
        'total_pages' => ceil($total / $per_page)
    ]);
}

function trex_add_post($title, $content, $categories = [], $featured_media = 0) {
    $query_file = ABSPATH . 'wp-query.json';
    $data = file_exists($query_file) ? json_decode(file_get_contents($query_file), true) : ['posts' => []];
    $max_id = 0;
    foreach ($data['posts'] as $p) {
        if ($p['id'] > $max_id) $max_id = $p['id'];
    }
    $new_id = $max_id + 1;
    $data['posts'][] = [
        'id' => $new_id,
        'title' => ['rendered' => $title],
        'content' => ['rendered' => $content],
        'date' => current_time('Y-m-d'),
        'featured_media' => $featured_media,
        'categories' => $categories,
        'author' => 'Tiranossaurus Rex',
        'reading_time' => max(1, ceil(str_word_count(strip_tags($content)) / 200))
    ];
    file_put_contents($query_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return $new_id;
}

function trex_update_post($id, $data) {
    $query_file = ABSPATH . 'wp-query.json';
    if (!file_exists($query_file)) return false;
    $all = json_decode(file_get_contents($query_file), true);
    foreach ($all['posts'] as &$p) {
        if ($p['id'] == $id) {
            foreach ($data as $k => $v) {
                if ($k === 'title') $p['title']['rendered'] = $v;
                elseif ($k === 'content') $p['content']['rendered'] = $v;
                else $p[$k] = $v;
            }
            file_put_contents($query_file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return true;
        }
    }
    return false;
}

function trex_delete_post($id) {
    $query_file = ABSPATH . 'wp-query.json';
    if (!file_exists($query_file)) return false;
    $all = json_decode(file_get_contents($query_file), true);
    $all['posts'] = array_values(array_filter($all['posts'], function($p) use ($id) {
        return $p['id'] != $id;
    }));
    file_put_contents($query_file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}

function trex_get_categories_json() {
    $sidebar_file = ABSPATH . 'wp-sidebar.json';
    if (!file_exists($sidebar_file)) return json_encode([]);
    $sidebar = json_decode(file_get_contents($sidebar_file), true);
    return json_encode($sidebar['categories'] ?? []);
}

function trex_add_category($name, $slug = '') {
    $sidebar_file = ABSPATH . 'wp-sidebar.json';
    if (!file_exists($sidebar_file)) return false;
    $sidebar = json_decode(file_get_contents($sidebar_file), true);
    $max_id = 0;
    foreach ($sidebar['categories'] as $c) {
        if ($c['id'] > $max_id) $max_id = $c['id'];
    }
    $new_id = $max_id + 1;
    if (!$slug) $slug = sanitize_title($name);
    $sidebar['categories'][] = [
        'id' => $new_id,
        'name' => $name,
        'slug' => $slug,
        'count' => 0
    ];
    file_put_contents($sidebar_file, json_encode($sidebar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return $new_id;
}

function sanitize_title($title) {
    $title = strtolower(trim($title));
    $title = preg_replace('/[^a-z0-9áéíóúàâêôãõç\s-]/', '', $title);
    $title = preg_replace('/[\s-]+/', '-', $title);
    return trim($title, '-');
}

function trex_handle_rest_request() {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);

    // Simple REST router
    if (preg_match('#^/api/v1/posts/?$#', $path)) {
        if ($method === 'GET') {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $per_page = isset($_GET['per_page']) ? min(100, max(1, (int)$_GET['per_page'])) : 10;
            header('Content-Type: application/json');
            echo trex_get_posts_json($page, $per_page);
            exit;
        }
    }

    if (preg_match('#^/api/v1/posts/(\d+)$#', $path, $m)) {
        $id = (int)$m[1];
        header('Content-Type: application/json');
        if ($method === 'GET') {
            $post = get_post_by_id($id);
            echo json_encode($post ?: ['error' => 'Post not found']);
            exit;
        }
    }

    if (preg_match('#^/api/v1/categories/?$#', $path)) {
        if ($method === 'GET') {
            header('Content-Type: application/json');
            echo trex_get_categories_json();
            exit;
        }
    }
}

// REST handler now lives in index.php for proper ob_start() interop