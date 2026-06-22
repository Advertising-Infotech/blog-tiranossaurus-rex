<?php
$sidebar_file = ABSPATH . 'wp-sidebar.json';
if (!file_exists($sidebar_file)) return;
$sidebar = json_decode(file_get_contents($sidebar_file), true);
?>
<aside id="sidebar" class="widget-area">
    <div class="widget">
        <h3 class="widget-title">Categorias</h3>
        <ul class="categories-list">
            <?php foreach ($sidebar['categories'] as $category) : ?>
                <li><a href="<?php echo get_category_link($category['id']); ?>"><?php echo $category['name']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="widget">
        <h3 class="widget-title">Redes Sociais</h3>
        <div class="social-links">
            <a href="<?php echo $sidebar['facebook_url']; ?>" target="_blank" rel="noopener" title="Facebook">
                <img src="<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png" alt="Facebook">
            </a>
            <a href="<?php echo $sidebar['instagram_url']; ?>" target="_blank" rel="noopener" title="Instagram">
                <img src="<?php echo get_template_directory_uri(); ?>/images/instagram-icon.png" alt="Instagram">
            </a>
            <a href="<?php echo $sidebar['twitter_url']; ?>" target="_blank" rel="noopener" title="Twitter">
                <img src="<?php echo get_template_directory_uri(); ?>/images/twitter-icon.png" alt="Twitter">
            </a>
        </div>
    </div>

    <div class="widget">
        <h3 class="widget-title">Buscar</h3>
        <?php get_search_form(); ?>
    </div>
</aside>
