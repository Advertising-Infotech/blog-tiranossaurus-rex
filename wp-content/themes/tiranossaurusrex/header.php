<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="site">
    <header class="site-header">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link">
            <img src="<?php echo get_template_directory_uri(); ?>/images/Facebook_Capa_14_de_julho_de_2018.jpg" alt="Tiranossaurus Rex">
        </a>
        <div class="header-text">
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <p class="site-description">Conhecimento é poder, que no coração cria sabedoria, luz, paz, amor e consciência, ligando a criatura ao seu Criador, Yahwah.</p>
        </div>
    </header>

    <nav class="main-navigation">
        <ul class="menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Notícias</a></li>
            <li><a href="<?php echo esc_url(home_url('/sobre')); ?>">Contato</a></li>
            <li><a href="<?php echo esc_url(home_url('/interativo')); ?>">Interativo</a></li>
            <li><a href="<?php echo esc_url(home_url('/members')); ?>">Members</a></li>
        </ul>
    </nav>

    <div class="ad-banner-top">
        <a href="http://www.magazinevoce.com.br/magazinetiranossaurusrex/" target="_blank" rel="noopener">
            <img src="<?php echo get_template_directory_uri(); ?>/images/Publicidade_cabeçalho.gif" alt="Publicidade">
        </a>
    </div>

    <div class="site-content">
        <div class="content-area">
