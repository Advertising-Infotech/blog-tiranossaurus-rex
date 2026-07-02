<?php get_header(); ?>

<div class="admin-page">
    <h1><span class="celestial-indicator"></span>Painel de Administração</h1>

    <?php if (isset($_GET['saved'])) : ?>
        <div class="success-msg">Configurações salvas com sucesso!</div>
    <?php endif; ?>

    <form method="post" action="<?php echo home_url('/admin'); ?>">
        <div class="form-group">
            <label>Google AdSense — Publisher ID</label>
            <input type="text" name="adsense_publisher" placeholder="ca-pub-XXXXXXXXXXXXXXXX" value="<?php echo htmlspecialchars(get_option('adsense_publisher', '')); ?>">
        </div>

        <div class="form-group">
            <label>Google AdSense — Ad Slot ID (para cards)</label>
            <input type="text" name="adsense_slot" placeholder="XXXXXXXXXX" value="<?php echo htmlspecialchars(get_option('adsense_slot', '')); ?>">
        </div>

        <div class="form-group">
            <label>Chaves de API (uma por linha, em ordem de fallback)</label>
            <textarea name="api_keys" placeholder="sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&#10;sk-ant-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&#10;AIzaxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"><?php echo htmlspecialchars(get_option('api_keys', '')); ?></textarea>
            <small style="color:rgba(255,246,223,0.4);font-size:0.75rem;">
                Ordene da mais prioritária para a menos. O sistema tenta cada chave em fallback automático.
            </small>
        </div>

        <div style="margin-top:25px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
            <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">API Key Interna</h3>
            <p style="font-size:0.78rem;color:rgba(255,246,223,0.5);margin-bottom:12px;">
                Gere uma chave de API para conectar serviços externos (n8n, OpenClaw, Hermes Agent, etc.)
            </p>
            <button type="submit" name="trex_api_key_generate" value="1" class="btn-save" style="background:linear-gradient(135deg,#FF0000,#FF4500);">Gerar Nova API Key</button>
            <?php
            $trex_keys = get_option('trex_api_keys', []);
            if (!empty($trex_keys) && is_array($trex_keys)) :
            ?>
            <div style="margin-top:15px;">
                <h4 style="font-size:0.8rem;color:rgba(255,246,223,0.4);margin-bottom:8px;">Chaves Ativas:</h4>
                <?php foreach ($trex_keys as $entry) : ?>
                <div style="background:rgba(0,0,0,0.3);border:1px solid rgba(0,227,253,0.1);border-radius:4px;padding:8px 12px;margin-bottom:6px;font-family:monospace;font-size:0.75rem;color:#00e3fd;word-break:break-all;">
                    <?php echo htmlspecialchars($entry['key'] ?? ''); ?>
                    <span style="color:rgba(255,246,223,0.3);font-size:0.65rem;display:block;">
                        <?php echo htmlspecialchars($entry['label'] ?? ''); ?> — <?php echo htmlspecialchars($entry['created'] ?? ''); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div style="margin-top:25px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
            <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">Redes Sociais — Compartilhamento Automático</h3>

            <div class="form-group">
                <label>Telegram — Bot Token</label>
                <input type="text" name="telegram_bot_token" placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" value="<?php echo htmlspecialchars(get_option('telegram_bot_token', '')); ?>">
            </div>
            <div class="form-group">
                <label>Telegram — Chat ID (pode ser negativo para grupos)</label>
                <input type="text" name="telegram_chat_id" placeholder="-1001234567890" value="<?php echo htmlspecialchars(get_option('telegram_chat_id', '')); ?>">
            </div>

            <div class="form-group">
                <label>Facebook — Page Access Token</label>
                <input type="text" name="facebook_page_token" placeholder="EAAx..." value="<?php echo htmlspecialchars(get_option('facebook_page_token', '')); ?>">
            </div>

            <div class="form-group">
                <label>Instagram — Access Token</label>
                <input type="text" name="instagram_token" placeholder="IGQVJ..." value="<?php echo htmlspecialchars(get_option('instagram_token', '')); ?>">
            </div>
            <div class="form-group">
                <label>Instagram — Business/Creator ID</label>
                <input type="text" name="instagram_id" placeholder="178414..." value="<?php echo htmlspecialchars(get_option('instagram_id', '')); ?>">
            </div>

            <div class="form-group">
                <label>TikTok — Access Token</label>
                <input type="text" name="tiktok_token" placeholder="ttb..." value="<?php echo htmlspecialchars(get_option('tiktok_token', '')); ?>">
            </div>
        </div>

        <button type="submit" class="btn-save">Salvar Configurações</button>
    </form>

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
        <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">API — Criar Post com Compartilhamento Automático</h3>
        <div style="font-size:0.78rem;color:rgba(255,246,223,0.6);line-height:1.8;">
            <p><strong style="color:#00e3fd;">POST /api/v1/posts</strong> — Cria post e compartilha nas redes automaticamente</p>
            <pre style="background:rgba(0,0,0,0.3);padding:10px;border-radius:4px;font-size:0.7rem;color:#00e3fd;overflow-x:auto;">
{
  "title": "Título da notícia",
  "content": "Conteúdo completo aqui...",
  "categories": [1, 2],
  "post_type": "article",
  "youtube_url": "https://youtube.com/watch?v=...",
  "audio_url": "https://...mp3",
  "gallery": ["https://...foto1.jpg", "https://...foto2.jpg"],
  "share": "auto"
}
            </pre>
            <p><strong style="color:#00e3fd;">GET /api/v1/share/{id}</strong> — Disparar compartilhamento manual de um post existente</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/options</strong> — Verificar status das configurações de redes sociais</p>
            <p><small style="color:rgba(255,246,223,0.3);">Após configurar os tokens acima, faça um POST /api/v1/posts com <code style="color:#00e3fd;">"share":"auto"</code> para testar.</small></p>
        </div>
    </div>

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
        <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">WhatsApp — Forçar Atualização do Cache</h3>
        <p style="font-size:0.78rem;color:rgba(255,246,223,0.5);margin-bottom:12px;">
            O WhatsApp (e Facebook) armazenam em cache o preview dos links. Use esta ferramenta para forçar uma re-varredura após alterar um post. Funciona também para Facebook, Messenger e Instagram.
        </p>
        <form method="post" action="<?php echo home_url('/admin'); ?>" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
            <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
                <label>ID do Post</label>
                <input type="number" name="whatsapp_cache_post_id" placeholder="Ex: 123" min="1">
            </div>
            <button type="submit" name="whatsapp_refresh_cache" value="1" class="btn-save" style="white-space:nowrap;">Atualizar Cache</button>
        </form>
        <?php if (isset($_GET['cache_refreshed'])): ?>
            <div class="success-msg" style="margin-top:10px;">Cache atualizado com sucesso! O WhatsApp agora deve mostrar o preview correto.</div>
        <?php endif; ?>
        <?php if (isset($_GET['cache_error'])): ?>
            <div class="error-msg" style="margin-top:10px;color:#ff6b6b;">Erro: <?php echo htmlspecialchars($_GET['cache_error']); ?></div>
        <?php endif; ?>
    </div>

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
        <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">REST API — Documentação</h3>
        <div style="font-size:0.78rem;color:rgba(255,246,223,0.6);line-height:1.8;">
            <p><strong style="color:#00e3fd;">GET /api/v1/posts</strong> — Listar posts (paginação: ?page=1&amp;per_page=10)</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/posts/{id}</strong> — Obter post por ID</p>
            <p><strong style="color:#00e3fd;">POST /api/v1/posts</strong> — Criar post com compartilhamento automático</p>
            <p><strong style="color:#00e3fd;">DELETE /api/v1/posts/{id}</strong> — Remover post</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/share/{id}</strong> — Compartilhar post nas redes sociais</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/refresh-cache/{id}</strong> — Forçar atualização do cache do WhatsApp/Facebook para o post</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/options</strong> — Verificar configurações das redes</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/categories</strong> — Listar categorias</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
