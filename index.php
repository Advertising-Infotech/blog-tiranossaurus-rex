<?php
require_once __DIR__ . '/wp-load.php';

$ABSPATH = defined('ABSPATH') ? ABSPATH : __DIR__ . '/';
$theme_dir = $ABSPATH . 'wp-content/themes/tiranossaurusrex/';

$functions_file = $theme_dir . 'functions.php';
if (file_exists($functions_file)) {
    require_once $functions_file;
}

ob_start();

$uri = $_SERVER['REQUEST_URI'];
$uri_path = parse_url($uri, PHP_URL_PATH);

// REST API handler
if (preg_match('#^/api/v1/#', $uri_path)) {
    $method = $_SERVER['REQUEST_METHOD'];

    // GET /api/v1/posts
    if ($uri_path === '/api/v1/posts' && $method === 'GET') {
        header('Content-Type: application/json');
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $per_page = isset($_GET['per_page']) ? min(100, max(1, (int)$_GET['per_page'])) : 10;
        echo trex_get_posts_json($page, $per_page);
        ob_end_flush();
        exit;
    }

    // GET /api/v1/posts/{id}
    if (preg_match('#^/api/v1/posts/(\d+)$#', $uri_path, $m) && $method === 'GET') {
        header('Content-Type: application/json');
        $post = get_post_by_id((int)$m[1]);
        echo json_encode($post ?: ['error' => 'Post not found']);
        ob_end_flush();
        exit;
    }

    // GET /api/v1/categories
    if ($uri_path === '/api/v1/categories' && $method === 'GET') {
        header('Content-Type: application/json');
        echo trex_get_categories_json();
        ob_end_flush();
        exit;
    }

    // POST /api/v1/posts — create with auto-sharing
    if ($uri_path === '/api/v1/posts' && $method === 'POST') {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'title is required']);
            ob_end_flush();
            exit;
        }
        $id = trex_add_post(
            $input['title'],
            $input['content'] ?? '',
            $input['categories'] ?? [],
            $input['featured_media'] ?? 0,
            [
                'post_type' => $input['post_type'] ?? 'article',
                'youtube_url' => $input['youtube_url'] ?? '',
                'audio_url' => $input['audio_url'] ?? '',
                'gallery' => $input['gallery'] ?? []
            ]
        );
        $share_result = null;
        if (!empty($input['share']) && $input['share'] !== 'none') {
            try { $share_result = trex_share_to_all($id); } catch (Exception $e) {
                $share_result = ['error' => $e->getMessage()];
            }
            // Auto-refresh Facebook/WhatsApp cache for the new post
            sleep(2);
            trex_refresh_facebook_cache(get_permalink($id));
        }
        http_response_code(201);
        echo json_encode([
            'id' => $id,
            'message' => 'Post created',
            'shared' => $share_result
        ]);
        ob_end_flush();
        exit;
    }

    // DELETE /api/v1/posts/{id}
    if (preg_match('#^/api/v1/posts/(\d+)$#', $uri_path, $m) && $method === 'DELETE') {
        header('Content-Type: application/json');
        $result = trex_delete_post((int)$m[1]);
        echo json_encode(['success' => $result]);
        ob_end_flush();
        exit;
    }

    // GET /api/v1/share/{id} — trigger sharing manually
    if (preg_match('#^/api/v1/share/(\d+)$#', $uri_path, $m) && $method === 'GET') {
        header('Content-Type: application/json');
        $result = trex_share_to_all((int)$m[1]);
        echo json_encode($result);
        ob_end_flush();
        exit;
    }

    // GET /api/v1/refresh-cache/{id} — force Facebook/WhatsApp cache refresh
    if (preg_match('#^/api/v1/refresh-cache/(\d+)$#', $uri_path, $m) && $method === 'GET') {
        header('Content-Type: application/json');
        $post = get_post_by_id((int)$m[1]);
        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            ob_end_flush();
            exit;
        }
        $url = get_permalink((int)$m[1]);
        $result = trex_refresh_facebook_cache($url);
        echo json_encode($result);
        ob_end_flush();
        exit;
    }

    // GET /api/v1/options — check social config
    if ($uri_path === '/api/v1/options' && $method === 'GET') {
        header('Content-Type: application/json');
        echo json_encode([
            'telegram_bot_token' => get_option('telegram_bot_token', ''),
            'telegram_chat_id' => get_option('telegram_chat_id', ''),
            'facebook_page_token' => get_option('facebook_page_token', ''),
            'instagram_token' => get_option('instagram_token', ''),
            'instagram_id' => get_option('instagram_id', ''),
            'tiktok_token' => get_option('tiktok_token', '')
        ]);
        ob_end_flush();
        exit;
    }
}

// Handle POST to /admin — save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri_path === '/admin') {
    if (isset($_POST['adsense_publisher'])) {
        update_option('adsense_publisher', $_POST['adsense_publisher']);
    }
    if (isset($_POST['adsense_slot'])) {
        update_option('adsense_slot', $_POST['adsense_slot']);
    }
    if (isset($_POST['api_keys'])) {
        update_option('api_keys', $_POST['api_keys']);
    }
    if (isset($_POST['telegram_bot_token'])) {
        update_option('telegram_bot_token', $_POST['telegram_bot_token']);
    }
    if (isset($_POST['telegram_chat_id'])) {
        update_option('telegram_chat_id', $_POST['telegram_chat_id']);
    }
    if (isset($_POST['facebook_page_token'])) {
        update_option('facebook_page_token', $_POST['facebook_page_token']);
    }
    if (isset($_POST['instagram_token'])) {
        update_option('instagram_token', $_POST['instagram_token']);
    }
    if (isset($_POST['instagram_id'])) {
        update_option('instagram_id', $_POST['instagram_id']);
    }
    if (isset($_POST['tiktok_token'])) {
        update_option('tiktok_token', $_POST['tiktok_token']);
    }
    if (isset($_POST['trex_api_key_generate']) && $_POST['trex_api_key_generate'] === '1') {
        $new_key = trex_generate_api_key();
        trex_register_api_key($new_key, 'Generated from Admin');
    }
    if (isset($_POST['whatsapp_refresh_cache']) && $_POST['whatsapp_refresh_cache'] === '1') {
        $post_id = (int)($_POST['whatsapp_cache_post_id'] ?? 0);
        if ($post_id > 0) {
            $url = get_permalink($post_id);
            $result = trex_refresh_facebook_cache($url);
            if ($result['success']) {
                header('Location: ' . home_url('/admin') . '?cache_refreshed=1');
                exit;
            } else {
                $err = urlencode('HTTP ' . $result['http_code'] . ': ' . ($result['response'] ?? 'unknown'));
                header('Location: ' . home_url('/admin') . '?cache_error=' . $err);
                exit;
            }
        }
    }
    header('Location: ' . home_url('/admin') . '?saved=1');
    exit;
}

if (defined('WP_ADMIN') && WP_ADMIN) {
    include $ABSPATH . 'wp-admin/index.php';
} else {
    if ($uri_path === '/' && isset($_GET['p'])) {
        include $theme_dir . 'single.php';
    } elseif (preg_match('/^\/post\/(\d+)$/', $uri_path, $matches)) {
        include $theme_dir . 'single.php';
    } elseif (preg_match('/^\/category\/([^\/]+)$/', $uri_path, $matches)) {
        include $theme_dir . 'category.php';
    } elseif ($uri_path === '/admin') {
        include $theme_dir . 'admin.php';
    } elseif (empty($uri_path) || $uri_path === '/') {
        include $theme_dir . 'index.php';
    } else {
        include $theme_dir . '404.php';
    }
}

ob_end_flush();
