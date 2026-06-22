<?php get_header(); ?>

<div class="error-404">
    <h1>404 — Página não encontrada</h1>
    <p>Desculpe, a página que você procura não existe ou foi removida.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="button">← Voltar para a página inicial</a>
</div>

<?php get_footer(); ?>
