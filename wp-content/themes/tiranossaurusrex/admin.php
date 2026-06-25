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

        <button type="submit" class="btn-save">Salvar Configurações</button>
    </form>

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid rgba(0,227,253,0.15);">
        <h3 style="color:#00e3fd;font-size:0.95rem;margin-bottom:12px;">REST API — Documentação</h3>
        <div style="font-size:0.78rem;color:rgba(255,246,223,0.6);line-height:1.8;">
            <p><strong style="color:#00e3fd;">GET /api/v1/posts</strong> — Listar posts (paginação: ?page=1&amp;per_page=10)</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/posts/{id}</strong> — Obter post por ID</p>
            <p><strong style="color:#00e3fd;">POST /api/v1/posts</strong> — Criar post (Body JSON: title, content, categories[], featured_media)</p>
            <p><strong style="color:#00e3fd;">DELETE /api/v1/posts/{id}</strong> — Remover post</p>
            <p><strong style="color:#00e3fd;">GET /api/v1/categories</strong> — Listar categorias</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
