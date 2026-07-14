# 🗺️ CHECKLIST — Blog Tiranossaurus Rex

> **Status atual do projeto** — Gerado em 14/Jul/2026

---

## 📋 Dados do Projeto

| Item | Valor |
|------|-------|
| **Projeto** | Blog Tiranossaurus Rex |
| **Site original** | `trexhacker.wixsite.com/tiranossaurusrex` |
| **Design** | Universe Deep Space (preto, gradientes, glow, HUD) |
| **Servidor** | PHP 8 built-in, porta **54549** |
| **URL local** | `http://192.168.1.66:54549/` |
| **GitHub** | `github.com/Advertising-Infotech/blog-tiranossaurus-rex` |
| **Fluxo** | Pasta Raiz (OneDrive) → `C:\trex` (deploy) → `C:\PHP` (servidor) |
| **Posts** | **1.163** scrapados do Wix (JSON) |
| **Imagens** | **928** imagens Wix baixadas (96.97% de cobertura) |
| **Último history** | `000054.md` (02/Jul/2026) |

---

## ✅ JÁ CONCLUÍDO

### Estrutura do Projeto
- [x] Pasta `specs/` com agents.md, design.md, gradients.json, pastas-de-trabalho.md, preferencias.md, prompt.md
- [x] Pasta `history/` com 54 sessões documentadas
- [x] Git init + remote + commits + push
- [x] `iniciar.bat` com servidor PHP built-in
- [x] `router.php` para roteamento limpo
- [x] `.env` com sistema `load_env()` + `trex_get_credential()`
- [x] Cache busting com `filemtime()` no CSS/JS

### Tema "Universe Deep Space"
- [x] **Header**: Foto perfil (90px, borda 4px glow) + 4 logotipos rotativos (60s ciclo) + indicador celestial (barra vermelha pulsante)
- [x] **Navigation**: Menu sticky (Notícias, Contato, Interativo, Membros, Admin), hamburger mobile, scroll-hide
- [x] **Banner rotativo 728x90**: 3 slides, 15s estático + 800ms transição, pause no hover
- [x] **Segundo espaço publicitário**: Responsivo, abaixo do banner
- [x] **Post cards**: Grid 3-col, 12 por página, infinite scroll via API `/api/v1/posts`, AdSense a cada 5 cards
- [x] **Sidebar**: Categorias (27), busca, espaço publicitário quadrado acima
- [x] **Footer**: 3 cards (azul/vermelho/branco) + logo + 5 ícones sociais (Facebook, Instagram, TikTok, Email, LinkedIn) + status bar high-tech com bolinhas piscando
- [x] **Single post**: OG meta tags, WhatsApp share, categorias
- [x] **Páginas**: Admin (`/admin`), 404, Em Construção
- [x] **Efeitos visuais**: Gradientes radiais de fundo, glow aleatório de `gradients.json` no hover dos cards, color cycling em bordas/títulos (4s), text-shadow shineGold

### Responsividade
- [x] Desktop (1200px max)
- [x] Tablet (1024px — 2 colunas, sidebar 260px)
- [x] Mobile (768px — 1 coluna, header full-width, hamburger, sidebar 100%)
- [x] Mobile pequeno (480px — fontes menores, foto 40px)

### Core Engine (wp-load.php)
- [x] Sistema de Actions/Filters (`add_action`, `do_action`, etc.)
- [x] Sistema de Asset Enqueue (`wp_enqueue_style/script`)
- [x] Query Loop (`have_posts`, `the_post`, `setup_postdata`)
- [x] REST API: `/api/v1/posts` (GET/POST/DELETE), `/api/v1/share/{id}`, `/api/v1/options`, `/api/v1/categories`
- [x] CRUD de posts via API: `trex_add_post()`, `trex_update_post()`, `trex_delete_post()`
- [x] API Key infrastructure: `trex_generate_api_key()`, `trex_validate_api_key()`
- [x] Sistema de cache: `trex_cache_get/set/delete()`, `trex_get_posts_cached()`
- [x] Dashboard com estatísticas: total posts, imagens, API keys, cache status
- [x] Suporte a Markdown renderizado como HTML nas páginas
- [x] Helpers: `trex_get_sidebar()`, `trex_get_navigation()`, `trex_get_header()`, `trex_get_footer()`, `trex_get_banner()`

### Funcionalidades
- [x] **Busca textual**: Endpoint `/api/v1/search?q=` com correspondência no título
- [x] **WhatsApp Share**: Link direto `wa.me/?text=...` no single.php
- [x] **Infinite Scroll**: JS carrega mais posts via API, substitui paginação tradicional
- [x] **Admin Dashboard**: `/admin` com visão geral, posts, imagens, API keys
- [x] **Admin — Upload de imagens**: Upload via API + galeria modal
- [x] **Admin — Gerenciar Posts**: Lista, criar, editar, deletar posts via admin UI
- [x] **Admin — Gerenciar Categorias**: Adicionar via admin UI
- [x] **Admin — Gerenciar API Keys**: Gerar, revogar, copiar
- [x] **Admin — Cache**: Limpar cache manualmente
- [x] **Admin — Newsletter**: Envio manual para lista de contatos
- [x] **Sidebar com Mês/Ano**: Navegação por arquivo mensal
- [x] **Compatibilidade Android**: Texto selecionável, links clicáveis, zoom controlado

### SEO & Analytics
- [x] Google Analytics 4 (G-0B63B41CEJ) — snippet no footer
- [x] Google AdSense (ca-pub-5644395578765638) — espaços reservados
- [x] Google Search Console — meta tag de verificação
- [x] Facebook Pixel — rastreamento de visitas
- [x] OG Tags — `og:title`, `og:description`, `og:image`, `og:url`, `og:type`
- [x] Twitter Cards — `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`
- [x] Schema.org JSON-LD — `NewsArticle` structured data
- [x] Sitemap.xml — gerado dinamicamente
- [x] Robots.txt — direciona para sitemap
- [x] Canonical URL em posts

---

## 🔄 EM ANDAMENTO / PENDENTE

### Integrações de Redes Sociais

| # | Item | Status | Observação |
|---|------|--------|------------|
| 1 | ❌ **Facebook — Login OAuth** | 🔴 Bloqueado | `Facebook Login` exige HTTPS (ngrok local só) |
| 2 | ❌ **Facebook — Compartilhar Post** | 🔴 Bloqueado | Precisa de `pages_manage_posts` + App Business + token |
| 3 | ❌ **Instagram — Compartilhar** | 🔴 Bloqueado | Depende do Facebook (token + permissões) |
| 4 | ❌ **TikTok — Compartilhar** | 🔴 Bloqueado | SDK exige HTTPS, publisher token separado |
| 5 | ❌ **WhatsApp Business API** | 🔴 Bloqueado | Exige número verificado + Meta Business |
| 6 | ❌ **Pinterest — Compartilhar** | 🔴 Bloqueado | Exige app no Pinterest Developers |
| 7 | ❌ **LinkedIn — Compartilhar API** | 🔴 Bloqueado | Exige app + OAuth 2.0 + token |
| 8 | ⏳ **Facebook — App Business** | 🟡 Pendente | Primeiro passo para desbloquear redes |

### WordPress Futuro

| # | Item | Observação |
|---|------|------------|
| 9 | ❌ Migrar para WordPress real | Quando o self-made estiver estável |
| 10 | ❌ Plugin de Newsletter | Mailchimp / SendGrid |
| 11 | ❌ Plugin de SEO | Yoast / RankMath |
| 12 | ❌ CDN | Cloudflare para assets |

### Endpoints Compartilhamento (API)
| # | Item | Status |
|---|------|--------|
| 13 | 🔲 WhatsApp | ✅ Funcional (link direto `wa.me`) |
| — | Facebook/Instagram/TikTok/Pinterest/LinkedIn | ❌ Resposta real | Placeholders já inseridos |

### Melhorias
| # | Item | Observação |
|---|------|------------|
| 14 | 🔲 Admin page refinada | UI básica, pode ser expandida |
| 15 | 🔲 Paginação de categorias | Links existem, mas sem filtro real |
| 16 | 🔲 Velocidade de carregamento | Otimizar imagens, lazy loading |

---

## 📁 ESTRUTURA DE ARQUIVOS

```
wordpress/
├── .env                        # Credenciais (DB, APIs, tokens)
├── .gitignore
├── index.php                   # Entry point (WordPress bootstrap)
├── router.php                  # Roteamento limpo (PHP built-in)
├── iniciar.bat                 # Inicia servidor PHP na porta 54549
├── wp-load.php                 # Core engine (CRUD, API, cache, helpers)
├── wp-config.php               # Configuração do WordPress
├── test.php                    # Página de teste
├── desktop.ini
│
├── specs/                      # Especificações do projeto
│   ├── agents.md
│   ├── design.md
│   ├── gradients.json
│   ├── pastas-de-trabalho.md
│   ├── preferencias.md
│   └── prompt.md
│
├── history/                    # Histórico de sessões (54 registros)
│   ├── 000001.md ... 000054.md
│
├── trash/                      # Arquivos descartados
│
├── Logotipo/                   # Logos do blog
│
├── wp-admin/                   # Painel admin do WordPress
├── wp-content/                 # Temas, plugins, uploads
│   └── themes/
│       └── tiranossaurusrex/   # Tema customizado
│           ├── style.css
│           ├── header.php
│           ├── footer.php
│           ├── index.php
│           ├── single.php
│           ├── functions.php
│           └── js/main.js
│
├── wp-includes/                # Core do WordPress
│
├── _debug_html.py              # Scrapers / debug (Python)
├── _debug_images.py
├── _debug_images2.py
├── _debug_post.
