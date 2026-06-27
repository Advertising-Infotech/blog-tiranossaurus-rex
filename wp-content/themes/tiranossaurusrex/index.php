<?php get_header(); ?>

<div class="posts-container" data-total="<?php echo (int)($GLOBALS['wp_total_posts'] ?? 0); ?>" data-theme="<?php echo get_template_directory_uri(); ?>">
    <?php if (have_posts()) : ?>
        <?php
        $ad_counter = 0;
        $ad_client = ''; // User sets this in admin: ca-pub-XXXXXXXXX
        $ad_slot   = ''; // User sets this in admin: XXXXXXXXXX
        ?>
        <?php 
        $post_index = 0;
        while (have_posts()) : the_post(); $post_index++; ?>
            <article class="post-card">
                <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:contents;">
                <div class="post-card-image">
                    <?php if ($post_index === 1) : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/vice-presidente-amma-2025.jpg" alt="Vice-presidente da AMMA">
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg" alt="Tiranossaurus Rex">
                    <?php endif; ?>
                </div>
                <div class="post-card-body">
                    <h2 class="post-card-title">
                        <?php the_title(); ?>
                    </h2>
                    <div class="post-card-meta">
                        <span><?php echo get_the_date('j M. Y'); ?></span>
                        <span><?php echo tiranossaurusrex_get_reading_time(); ?> min</span>
                    </div>
                    <div class="post-card-excerpt">
                        <?php echo wp_trim_words(get_the_content(), 18); ?>
                    </div>
                    <span class="post-card-read-more">Ler mais →</span>
                </div>
                </a>
            </article>
            <?php
            $ad_counter++;
            if ($ad_counter % 5 === 0) :
            ?>
            <article class="post-card ad-card" data-ad>
                <div class="ad-content">
                    <div class="ad-label">Publicidade</div>
                    <div class="ad-placeholder">
                        Google AdSense<br>
                        <small style="font-size:0.7rem;opacity:0.5;">
                            Configure seu Publisher ID no Admin
                        </small>
                    </div>
                </div>
            </article>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="load-more-container">
    <button class="load-more-btn">Carregar mais notícias ↓</button>
</div>

<?php get_footer(); ?>
