<?php get_header(); ?>

<header class="archive-header">
    <h1 class="archive-title"><?php single_cat_title(); ?></h1>
</header>

<div class="posts-container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
