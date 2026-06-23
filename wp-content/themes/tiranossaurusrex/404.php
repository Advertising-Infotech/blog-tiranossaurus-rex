<?php get_header(); ?>

<div class="error-404">
    <h1>404</h1>
    <p>Página não encontrada. O conteúdo pode ter sido removido ou o link está quebrado.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">← Voltar para o início</a>
</div>

<?php get_footer(); ?>
