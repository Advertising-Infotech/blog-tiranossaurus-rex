<?php
$sidebar_file = ABSPATH . 'wp-sidebar.json';
if (!file_exists($sidebar_file)) return;
$sidebar = json_decode(file_get_contents($sidebar_file), true);
?>
<aside id="sidebar" class="widget-area">
    <!-- Espaço publicitário quadrado -->
    <div class="sidebar-ad-square">
        <span class="ad-label">Publicidade</span>
        <div class="ad-content">
            <div class="ad-placeholder-text">
                Anuncie Aqui<br>
                <small style="font-size:0.55rem;opacity:0.5;">quadrado</small>
            </div>
        </div>
    </div>

    <div class="widget">
        <h3 class="widget-title">Categorias</h3>
        <ul class="categories-list">
            <?php foreach ($sidebar['categories'] as $category) : ?>
                <li><a href="<?php echo get_category_link($category['id']); ?>"><?php echo $category['name']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="widget">
        <h3 class="widget-title">Busca</h3>
        <?php get_search_form(); ?>
    </div>
</aside>
