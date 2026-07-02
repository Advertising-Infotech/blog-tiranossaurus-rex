<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache, must-revalidate">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <?php
    global $post;
    $og_title = 'Tiranossaurus Rex - O Blog mais perigoso do Brasil';
    $og_description = 'Informação, política, economia e entretenimento sem filtros. O blog que a mídia não quer que você leia.';
    $og_image = get_template_directory_uri() . '/images/Facebook_Capa_14_de_julho_de_2018.jpg';
    $og_url = WP_HOME;
    $og_type = 'website';

    if (!empty($post) && !empty($post['id'])) {
        $post_title = $post['title']['rendered'] ?? '';
        $post_content = $post['content']['rendered'] ?? '';
        $post_excerpt = strip_tags($post_content);
        $post_excerpt = mb_strlen($post_excerpt) > 350 ? mb_substr($post_excerpt, 0, 347) . '...' : $post_excerpt;

        if ($post_title) {
            $og_title = $post_title;
            $og_description = $post_excerpt ?: $og_description;
            $og_type = 'article';
            $og_url = get_permalink($post['id']);
        }
        if (!empty($post['featured_image_url'])) {
            $og_image = $post['featured_image_url'];
        }
    }
    ?>
    <meta property="og:title" content="<?php echo htmlspecialchars($og_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($og_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($og_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="<?php echo $og_type; ?>">
    <meta property="og:site_name" content="Tiranossaurus Rex">
    <meta property="og:locale" content="pt_BR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($og_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($og_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
    <?php wp_head(); ?>
    <style>
        <?php
        $gradients_file = ABSPATH . 'gradients.json';
        if (file_exists($gradients_file)) {
            echo 'window.TGRADIENTS = ' . file_get_contents($gradients_file) . ';';
        }
        ?>
    </style>
</head>
<body <?php body_class(); ?>>
<div class="site">
    <div class="header-wrapper">
    <header class="site-header">
        <div class="header-left">
            <img src="<?php echo get_template_directory_uri(); ?>/images/Facebook_Capa_14_de_julho_de_2018.jpg" alt="Tiranossaurus Rex" class="profile-photo">
            <div class="header-text-block">
                <h1><span class="celestial-indicator"></span><?php bloginfo('name'); ?></h1>
                <p class="tagline"><?php bloginfo('description'); ?></p>
            </div>
        </div>
        <div class="header-right">
            <div class="logo-rotation">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-1.png" alt="Logotipo 1">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-2.png" alt="Logotipo 2">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-3.png" alt="Logotipo 3">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-4.png" alt="Logotipo 4">
            </div>
        </div>
    </header>
    </div>

    <nav class="main-navigation">
        <button class="hamburger-toggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul class="menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Notícias</a></li>
            <li><a href="<?php echo esc_url(home_url('/sobre')); ?>">Contato</a></li>
            <li><a href="<?php echo esc_url(home_url('/interativo')); ?>">Interativo</a></li>
            <li><a href="<?php echo esc_url(home_url('/members')); ?>">Membros</a></li>
            <li><a href="<?php echo esc_url(home_url('/admin')); ?>">Admin</a></li>
        </ul>
    </nav>

    <!-- Banner rotativo 728x90 -->
    <div class="banner-rotativo">
        <div class="banner-track">
            <div class="banner-slide">
                <a href="#" target="_blank" rel="noopener">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Publicidade_cabeçalho.gif" alt="Publicidade">
                </a>
            </div>
            <div class="banner-slide">
                <a href="#" target="_blank" rel="noopener">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg" alt="Publicidade">
                </a>
            </div>
            <div class="banner-slide">
                <a href="https://advertisingpropaganda.yolasite.com" target="_blank" rel="noopener">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/banner-anuncio-728x90.jpg" alt="Anuncie Aqui" onerror="this.parentElement.innerHTML='<div class=banner-placeholder><span>ANUNCIE AQUI</span><small>728x90</small></div>'">
                </a>
            </div>
        </div>
    </div>

    <!-- Segundo espaço publicitário -->
    <div class="ad-space-secondary">
        <div class="ad-container">
            <a href="https://advertisingpropaganda.yolasite.com" target="_blank" rel="noopener">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg" alt="Publicidade">
            </a>
        </div>
    </div>

    <div class="site-content">
        <div class="content-area">
