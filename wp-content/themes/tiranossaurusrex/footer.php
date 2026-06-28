        </div>
        <?php get_sidebar(); ?>
    </div>

    <footer class="site-footer">
        <div class="footer-cards">
            <div class="footer-card blue">
                <h3>Informação</h3>
                <ul class="footer-list">
                    <li>Conhecimento é poder, que no coração cria sabedoria, luz, paz, amor e consciência</li>
                    <li>Ligando a criatura ao seu Criador, Yahwah, em busca da verdade eterna</li>
                    <li>A informação correta liberta o ser humano das amarras da ignorância</li>
                    <li>Busque sempre o conhecimento com discernimento e responsabilidade</li>
                </ul>
            </div>
            <div class="footer-card red">
                <h3>Destaque</h3>
                <ul class="footer-list">
                    <li>Fique por dentro das últimas notícias e atualizações de Senador Canedo e região</li>
                    <li>Acompanhe os eventos, as sessões da câmara e as decisões políticas locais</li>
                    <li>Participe ativamente da vida comunitária e exerça sua cidadania</li>
                    <li>Senador Canedo em primeiro lugar, com informação de qualidade</li>
                </ul>
            </div>
            <div class="footer-card white">
                <h3>Contato</h3>
                <ul class="footer-list">
                    <li>Entre em contato através das nossas redes sociais ou envie sua sugestão de pauta</li>
                    <li>Siga-nos no Facebook, Instagram, Twitter e Google+ para ficar atualizado</li>
                    <li>Compartilhe suas ideias e contribua com o jornalismo independente</li>
                    <li>Juntos podemos fazer a diferença na informação da nossa cidade</li>
                </ul>
            </div>
        </div>

        <div class="footer-content">
            <div class="footer-logo-area">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Facebook_Capa_14_de_julho_de_2018.jpg" alt="Tiranossaurus Rex">
                <h2>Tiranossaurus Rex</h2>
            </div>
            <p class="footer-subtitle">Blog de Atualidades</p>
            <p class="footer-desc">Social media influencer</p>

            <div class="footer-social">
                <a href="https://www.facebook.com/t.rex.hacker" target="_blank" rel="noopener" title="Facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png" alt="Facebook"></a>
                <a href="https://www.instagram.com/tiranossaurusrex/" target="_blank" rel="noopener" title="Instagram"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram-icon.png" alt="Instagram"></a>
                <a href="https://tiktok.com/@tiranossaurus_rex" target="_blank" rel="noopener" title="TikTok"><img src="<?php echo get_template_directory_uri(); ?>/images/tiktok-icon.svg" alt="TikTok"></a>
                <a href="mailto:advertisingpropaganda@gmail.com" title="E-mail"><img src="<?php echo get_template_directory_uri(); ?>/images/email-icon.svg" alt="E-mail"></a>
                <a href="https://www.linkedin.com/in/advertising-propaganda-162220185?utm_source=share_via&utm_content=profile&utm_medium=member_android" target="_blank" rel="noopener" title="LinkedIn"><img src="<?php echo get_template_directory_uri(); ?>/images/linkedin-icon.svg" alt="LinkedIn"></a>
            </div>

            <div class="site-info">
                <p>&copy; 2018 | todos os direitos reservados.</p>
                <p>Administrado por <strong>Advertising Tecnologia da Informação & Comunicação Social EIRELI</strong>, inscrito no <strong>CNPJ nº 34.346.223/0001-27</strong></p>
            </div>
        </div>
    </footer>
</div>

<!-- Status Bar -->
<div class="api-status-bar">
    <div class="status-section">
        <span class="status-dot blink-red"></span>
        <span class="status-text" id="status-text-red">API: Aguardando consulta</span>
    </div>
    <div class="status-section">
        <span class="status-dot blink-green"></span>
        <span class="status-text" id="status-text-green">Tiranossaurus Rex Engine</span>
    </div>
    <div class="status-section">
        <span class="status-rotating" id="status-rotating-tech"></span>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
