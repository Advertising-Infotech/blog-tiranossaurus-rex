<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="post-single">
        <header class="post-header">
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-meta">
                <span class="post-date"><?php echo get_the_date('j M. Y'); ?></span>
                <span class="post-author">Tiranossaurus Rex</span>
                <span class="post-reading-time"><?php echo tiranossaurusrex_get_reading_time(); ?> min de leitura</span>
            </div>
        </header>

        <div class="post-content">
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="post-body">
                <?php the_content(); ?>
            </div>
        </div>

        <footer class="post-footer">
            <div class="post-categories">
                Categorias: <?php the_category(' '); ?>
            </div>
        </footer>
    </article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
