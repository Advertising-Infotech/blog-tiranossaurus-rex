---
name: "{{PROJECT_NAME}} Master Design"
version: 1.2.0
references:
  - ./Briefing.md
  - ./gradients.json
colors:
  background: "#000000"
  primary: "#fff6df"
  secondary: "#00e3fd"
  tertiary: "#0055ff"
  accent: "#FF0000"
typography:
  header-font-size: "clamp(2.5rem, 6vw, 5rem)"
  header-font-weight: "700"
  body-font-size: "1rem"
---

## Overview
Diretriz final do projeto {{PROJECT_NAME}}. Foco em estética "Universe Deep Space".

## Branding
- **Nome do Projeto**: `{{PROJECT_NAME}}`
- **Logotipos**: `{{NUMERO_DE_LOGOTIPOS_NO_SISTEMA_DE_RODIZIO}}` logotipos em sistema de rodízio.
## Header Structure
  - Título Grande: `{{PROJECT_NAME}}` · Branco · `font-size: clamp(2.5rem, 6vw, 5rem)`
  - Texto Pequeno à Esquerda: "Todos os direitos reservados ®" · Branco
  - Logotipo: Canto superior esquerdo

## Colors
- **Universe Background**: Fundo obrigatoriamente Preto Absoluto (#000000).
- **Mandatory Gradients**: Toda página deve possuir 2 gradientes radiais sobrepostos:
  1. Gradiente em tonalidades de **Azul Estelar**.
  2. Gradiente em tonalidades de **Turquesa/Ciano (Atmospheric)**.
  *As posições destes gradientes podem variar por página para dinamismo visual.*
- **Accents**: 
  - **Shine Glow**: Títulos com animação Gold-Yellow-White e text-shadow pulsante.
  - **Celestial Indicator**: Traço Vermelho Vertical dinâmico no início de cada título.
- **Interactions**: Cards com glow azul intenso, movimento translateY no hover, e efeito glow‑blur‑flare aleatório selecionado de `gradients.json` (ver `./gradients.json` na raiz do projeto) a cada mouseover. Os efeitos de hover devem sortear aleatoriamente um gradiente deste arquivo a cada nova interação.

## Typography
- **Header**: `font-size: clamp(2.5rem, 6vw, 5rem)` · `font-weight: 700`
- **Body**: `font-size: 1rem` · `font-weight: 400`
- *Valores sobrescrevíveis por projeto via as variáveis `header-font-size` e `body-font-size` no frontmatter.*

## Components
- **Text Cards**: Três quadrados sintonizados no rodapé:
  - Azul: `{{CONTEUDO_DO_CARD_AZUL}}`
  - Vermelho: `{{CONTEUDO_DO_CARD_VERMELHO}}`
  - Branco: `{{CONTEUDO_DO_CARD_BRANCO}}`
- **Motion Frames**: Molduras HUD com scanlines e labels de dados revezadas.

---

## Dynamic Effects — Machine Instructions

### Efeito 1 — OnMouseOver Glow/Blur/Flare Aleatório em Cards

```
TRIGGER: onmouseover em qualquer card de texto, card de feature,
         card de preço, card de depoimento ou qualquer elemento
         do tipo card presente na página.

COMPORTAMENTO OBRIGATÓRIO:
  1. A cada evento mouseover, sortear aleatoriamente UM gradiente
     do arquivo ./gradients.json (índice aleatório entre 0 e length-1).
  2. Aplicar o gradiente sorteado como efeito de glow/blur/flare
     posicionado ATRÁS do card (z-index inferior ao card).
  3. O gradiente sorteado NUNCA pode ser o mesmo do mouseover anterior
     para o mesmo card — implementar exclusão do último índice usado.
  4. Transição de entrada do efeito: 0.3s ease-in-out.
  5. Transição de saída (mouseleave): 0.5s ease-out, opacity 0.

IMPLEMENTAÇÃO DE REFERÊNCIA:
  const gradients = await fetch('./gradients.json').then(r => r.json());
  let lastIndex = -1;

  card.addEventListener('mouseover', () => {
    let idx;
    do { idx = Math.floor(Math.random() * gradients.length); }
    while (idx === lastIndex);
    lastIndex = idx;

    const g = gradients[idx];
    card.style.setProperty('--glow-start', g.start);
    card.style.setProperty('--glow-end',   g.end);
    card.style.setProperty('--glow-dir',   g.direction ?? '135deg');
    card.classList.add('glow-active');
  });

  card.addEventListener('mouseleave', () => {
    card.classList.remove('glow-active');
  });

CSS:
  .card { position: relative; }
  .card::before {
    content: '';
    position: absolute;
    inset: -8px;
    z-index: -1;
    border-radius: inherit;
    background: linear-gradient(var(--glow-dir), var(--glow-start), var(--glow-end));
    filter: blur(18px);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  .card.glow-active::before { opacity: 0.75; }
```

---

### Efeito 2 — Ciclo Contínuo de Cores em Títulos e Bordas de Cards

```
ELEMENTOS AFETADOS:
  - Todos os títulos H1, H2, H3 da página
  - As bordas (border/outline) de todos os cards

COMPORTAMENTO OBRIGATÓRIO:
  1. No carregamento da página, embaralhar a lista de gradientes
     do ./gradients.json em ordem aleatória.
  2. Fazer um ciclo infinito e suave pelas cores embaralhadas:
     um gradiente entra, outro sai, nunca para, nunca repete
     na mesma sequência entre sessões.
  3. Títulos: aplicar o gradiente como background-clip text
     (preenchimento das letras muda de cor suavemente).
  4. Bordas de cards: aplicar o gradiente como border-image
     (a borda do card muda de cor suavemente).
  5. Duração de cada transição entre gradientes: 3s ease-in-out.
  6. Intervalo entre trocas: 2s (total de ~5s por gradiente).
  7. Títulos e bordas NÃO precisam estar sincronizados entre si —
     podem ciclar em fases independentes para maximizar dinamismo.

IMPLEMENTAÇÃO DE REFERÊNCIA:
  async function startColorCycle(elements, property) {
    const raw = await fetch('./gradients.json').then(r => r.json());
    // Fisher-Yates shuffle
    const list = [...raw].sort(() => Math.random() - 0.5);
    let i = 0;

    setInterval(() => {
      const g   = list[i % list.length];
      const css = `linear-gradient(${g.direction ?? '135deg'}, ${g.start}, ${g.end})`;
      elements.forEach(el => {
        el.style.transition = 'all 3s ease-in-out';
        if (property === 'text') {
          el.style.backgroundImage      = css;
          el.style.webkitBackgroundClip = 'text';
          el.style.webkitTextFillColor  = 'transparent';
          el.style.backgroundClip       = 'text';
        } else if (property === 'border') {
          el.style.borderImage  = css + ' 1';
          el.style.borderStyle  = 'solid';
          el.style.borderWidth  = '1px';
        }
      });
      i++;
    }, 5000); // troca a cada 5s (3s transição + 2s pausa)
  }

  // Inicializar ao carregar a página
  startColorCycle(document.querySelectorAll('h1, h2, h3'), 'text');
  startColorCycle(document.querySelectorAll('.card'),      'border');

NOTA PARA O AGENTE:
  Em frameworks como React/Vue/Svelte, implementar via
  useState/ref + useEffect/onMounted com clearInterval
  no cleanup para evitar memory leak.
  Em Next.js com SSR, garantir que o ciclo inicie apenas
  no cliente (typeof window !== 'undefined').
```
