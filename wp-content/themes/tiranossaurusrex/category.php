<?php get_header(); ?>

<header class="archive-header">
    <h1 class="archive-title"><?php single_cat_title(); ?></h1>
</header>

<div class="posts-container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="post-card">
            <div class="post-card-image">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg" alt="Tiranossaurus Rex">
                <?php endif; ?>
            </div>
            <div class="post-card-body">
                <h2 class="post-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="post-card-meta">
                    <span><?php echo get_the_date('j M. Y'); ?></span>
                    <span><?php echo tiranossaurusrex_get_reading_time(); ?> min</span>
                </div>
                <div class="post-card-excerpt">
                    <?php echo wp_trim_words(get_the_content(), 18); ?>
                </div>
                <a href="<?php the_permalink(); ?>" class="post-card-read-more">Ler mais →</a>
            </div>
        </article>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
