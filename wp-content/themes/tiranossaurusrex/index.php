<?php get_header(); ?>

<div class="posts-container" data-total="<?php echo (int)($GLOBALS['wp_total_posts'] ?? 0); ?>">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
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
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="load-more-container">
    <button class="load-more-btn">Carregar mais notícias ↓</button>
</div>

<?php
$total_pages = $GLOBALS['wp_total_pages'] ?? 1;
$current_page = $GLOBALS['wp_current_page'] ?? 1;
if ($total_pages > 1) :
?>
<div class="pagination-area">
    <?php if ($current_page > 1) : ?>
        <a href="<?php echo home_url('/inicio/page/' . ($current_page - 1)); ?>" class="pagination-btn">← Anterior</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <a href="<?php echo home_url('/inicio/page/' . $i); ?>" class="pagination-btn"><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if ($current_page < $total_pages) : ?>
        <a href="<?php echo home_url('/inicio/page/' . ($current_page + 1)); ?>" class="pagination-btn">Próximo →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php get_footer(); ?>
