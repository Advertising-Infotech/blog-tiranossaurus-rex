---
documento: pastas-de-trabalho
versao: "1.0.0"
publico: AGENTE-DE-IA
escopo: REGRA-ABSOLUTA-DIRETORIOS
---

# PASTAS DE TRABALHO DO SISTEMA

> **REGRIA ABSOLUTA:** O agente de IA só pode mexer nestas 3 pastas.
> Nenhuma outra pasta do sistema pode ser criada, modificada ou acessada
> sem autorização explícita do operador.

---

## 1. PASTA RAIZ DO PROJETO (Fonte da Verdade)

```
C:\Users\lagar\OneDrive\Área de Trabalho 2024\BackUp\Advertising TI & CS\Projetos\Blog Tiranossaurus Rex\wordpress
```

**Função:** Diretório principal do projeto. Contém todos os arquivos fonte,
especificações, histórico e git. **Todas as edições devem ser feitas aqui primeiro.**

**O que contém:**
- `wp-content/themes/tiranossaurusrex/` — Tema WordPress (PHP, CSS, JS)
- `specs/` — Documentos de especificação (regras, design, preferências)
- `history/` — Log imutável de sessões de trabalho
- `.git/` — Repositório git (push para GitHub)

**Regra:** É aqui que se edita, commita e faz push.

---

## 2. PASTA PARA PUBLICAÇÃO ONLINE (Deploy)

```
C:\trex
```

**Função:** Cópia de publicação servida pelo PHP built-in server.
O servidor PHP roda com `ABSPATH` apontando para esta pasta.

**Regra:** Depois de editar na PASTA RAIZ, copiar os arquivos modificados
para esta pasta para que o servidor reflita as mudanças.

**Arquivos que devem ser sincronizados:**
- `wp-content/themes/tiranossaurusrex/` (todos os arquivos de tema)
- `gradients.json` (na raiz)

---

## 3. SERVIDOR PHP

```
C:\PHP
```

**Função:** Instalação do PHP que roda o servidor built-in.

**Regra:** Não editar nada aqui. Usar apenas para iniciar/parar o servidor.

**Comando de inicialização do servidor:**
```
C:\PHP\php.exe -S localhost:8088 -t C:\trex
```

---

## Fluxo de Trabalho Obrigatório

```
1. EDITAR → na PASTA RAIZ (OneDrive)
2. COPIAR  → arquivos modificados para C:\trex
3. REGISTRAR → history/ com relatório completo
4. COMMIT + PUSH → no git da PASTA RAIZ
```
