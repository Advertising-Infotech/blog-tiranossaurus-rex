<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
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
                <img src="<?php echo get_template_directory_uri(); ?>/images/Logotipo%201.png" alt="Logotipo 1">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Logotipo%202.png" alt="Logotipo 2">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Logotipo%203.png" alt="Logotipo 3">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Logotipo%204.png" alt="Logotipo 4">
            </div>
        </div>
    </header>

    <nav class="main-navigation">
        <ul class="menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Notícias</a></li>
            <li><a href="<?php echo esc_url(home_url('/sobre')); ?>">Contato</a></li>
            <li><a href="<?php echo esc_url(home_url('/interativo')); ?>">Interativo</a></li>
            <li><a href="<?php echo esc_url(home_url('/members')); ?>">Members</a></li>
            <li><a href="<?php echo esc_url(home_url('/admin')); ?>">Admin</a></li>
        </ul>
    </nav>

    <div class="site-content">
        <div class="content-area">
