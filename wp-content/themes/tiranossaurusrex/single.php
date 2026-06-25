<?php
$requested_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
$post_data = $requested_id ? get_post_by_id($requested_id) : null;

if ($post_data) {
    setup_postdata($post_data);
    $GLOBALS['post'] = $post_data;
}

get_header();
?>

<?php if ($post_data) : ?>
    <article class="post-single">
        <header class="post-header">
            <h1 class="post-title"><span class="celestial-indicator"></span><?php the_title(); ?></h1>
            <div class="post-meta">
                <span><?php echo get_the_date('j M. Y'); ?></span>
                <span>Tiranossaurus Rex</span>
                <span><?php echo tiranossaurusrex_get_reading_time(); ?> min de leitura</span>
            </div>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <div class="post-featured-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="post-body">
            <?php the_content(); ?>
        </div>

        <footer class="post-footer">
            <div class="post-categories">
                <?php the_category(' '); ?>
            </div>
        </footer>
    </article>
<?php else : ?>
    <?php $const_path = locate_template('em-construcao.php'); if ($const_path) include $const_path; ?>
<?php endif; ?>

<?php get_footer(); ?>
