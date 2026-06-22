<?php get_header(); ?>

<div class="banner-carousel">
    <div class="banner-item">
        <img src="<?php echo get_template_directory_uri(); ?>/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg" alt="Banner para o Blog Tiranossaurus Rex">
    </div>
    <div class="banner-item">
        <img src="<?php echo get_template_directory_uri(); ?>/images/Publicidade_cabeçalho.gif" alt="Publicidade">
    </div>
    <div class="banner-item">
        <img src="<?php echo get_template_directory_uri(); ?>/images/vice-presidente-da-AMMA-Senador-Canedo-é-finalista-do-Prêmio-Espírito-Público-2025.jpg" alt="Vice-presidente da AMMA Senador Canedo é finalista do Prêmio Espírito Público 2025">
    </div>
</div>

<div class="posts-container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article class="post">
                <div class="post-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php endif; ?>
                </div>
                <div class="post-content">
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date('j M. Y'); ?></span>
                        <span class="post-author">Tiranossaurus Rex</span>
                        <span class="post-reading-time"><?php echo tiranossaurusrex_get_reading_time(); ?> min de leitura</span>
                    </div>
                    <div class="post-excerpt">
                        <?php echo wp_trim_words(get_the_content(), 25); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="read-more">Leia mais →</a>
                </div>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php tiranossaurusrex_pagination(); ?>
</div>

<?php get_footer(); ?>
