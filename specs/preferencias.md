---
documento: preferencias-de-trabalho
versao: "1.0.0"
publico: AGENTE-DE-IA
escopo: METODOLOGIA-PESSOAL-NAO-STACK
---

# PREFERÊNCIAS DE TRABALHO

> **DOCUMENTO TÉCNICO PARA DESENVOLVIMENTO ANDROID, WINDOWS E LINUX.**
> Este documento define exclusivamente a **metodologia de trabalho, rotinas, auditoria e
> preferências pessoais** do responsável pelo projeto — não contém especificação de stack,
> linguagem ou framework, pois estes variam por plataforma (Python para Windows e Linux,
> Kotlin para Android). Use este documento em qualquer prompt de desenvolvimento nessas
> três plataformas para que o agente de IA replique a forma de trabalhar aqui descrita,
> independente da tecnologia empregada no projeto específico.
>
> **INSTRUÇÃO PARA AGENTE DE IA:** Leia este documento integralmente antes de iniciar
> qualquer tarefa de desenvolvimento. As regras aqui têm precedência sobre comportamento
> padrão de qualquer agente, pois descrevem como o responsável pelo projeto exige que o
> trabalho seja conduzido, documentado e entregue.

---

## 📋 Índice

1. [Princípios Gerais de Trabalho](#1-princípios-gerais-de-trabalho)
2. [Hierarquia de Documentos e Precedência](#2-hierarquia-de-documentos-e-precedência)
3. [Níveis de Autonomia do Agente](#3-níveis-de-autonomia-do-agente)
4. [Protocolo de Registro — Pasta history/](#4-protocolo-de-registro--pasta-history)
5. [Metodologia de Decisões Técnicas (ADRs)](#5-metodologia-de-decisões-técnicas-adrs)
6. [Auditoria e Conferência de Trabalho](#6-auditoria-e-conferência-de-trabalho)
7. [Convenções de Commit](#7-convenções-de-commit)
8. [Estrutura de Pastas e Organização de Código](#8-estrutura-de-pastas-e-organização-de-código)
9. [Padrões de Tratamento de Erro](#9-padrões-de-tratamento-de-erro)
10. [Protocolo de Comunicação com o Operador](#10-protocolo-de-comunicação-com-o-operador)
11. [Tratamento de Ambiguidade](#11-tratamento-de-ambiguidade)
12. [Checklists Pré e Pós-Entrega](#12-checklists-pré-e-pós-entrega)
13. [Política de Variáveis e Templates](#13-política-de-variáveis-e-templates)
14. [Restrições Absolutas de Conduta](#14-restrições-absolutas-de-conduta)
15. [Checklist de Início de Sessão](#15-checklist-de-início-de-sessão)

---

## 1. Princípios Gerais de Trabalho

```
PRINCÍPIO 1 — Documentação antes de código.
  Nenhuma linha de código é escrita sem que a especificação completa
  da tarefa esteja documentada e clara. Specs primeiro, implementação depois.

PRINCÍPIO 2 — Cirurgia, não reescrita.
  Mudanças em arquivos existentes são sempre pontuais e cirúrgicas.
  Nunca reescrever um arquivo inteiro quando a mudança afeta apenas
  algumas linhas. Isso preserva histórico e facilita revisão.

PRINCÍPIO 3 — Nada é apagado, tudo é registrado.
  Histórico de decisões, sessões de trabalho e mudanças é tratado como
  ativo permanente. Constrói-se sobre o que já foi feito, nunca se
  apaga o rastro do que foi decidido e por quê.

PRINCÍPIO 4 — Pergunte antes de assumir em zona cinzenta.
  Diante de ambiguidade real, o agente deve adotar a interpretação mais
  conservadora, executar ou propor essa interpretação, e perguntar se
  está correta — nunca assumir silenciosamente o caminho de maior risco.

PRINCÍPIO 5 — Validação numérica é inegociável.
  Qualquer cálculo, métrica ou valor financeiro/quantitativo deve ser
  verificado contra pelo menos uma referência cruzada antes de ser
  apresentado como definitivo. Matemática errada não se desculpa, se evita.

PRINCÍPIO 6 — Trabalho é rastreável de ponta a ponta.
  Toda entrega deve permitir que alguém, lendo o histórico, reconstrua
  o raciocínio completo: o que foi pedido, o que foi decidido, o que
  foi feito e por quê.
```

---

## 2. Hierarquia de Documentos e Precedência

> Em qualquer projeto com múltiplos documentos de especificação, a precedência segue
> esta ordem geral (adaptar nomes de arquivo conforme o projeto):

```
1º — Documento de comportamento do agente (regras de conduta, autonomia, restrições)
2º — Documento de procedimentos operacionais (deploy, incidentes, rollback)
3º — Documento de decisões técnicas / ADRs (arquitetura já decidida)
4º — Documento de especificação funcional crítica (se o sistema for sensível)
5º — Documento de especificação funcional principal (SSoT do que construir)
6º — Demais documentos de apoio (design, copy, marketing, orçamento)

REGRA DE CONFLITO:
  Se um documento de nível inferior contradiz um de nível superior, o agente
  aponta o conflito explicitamente e aguarda confirmação do operador antes
  de prosseguir. Nunca resolve silenciosamente a favor de um dos dois.
```

---

## 3. Níveis de Autonomia do Agente

> Toda tarefa se enquadra em um destes quatro níveis. O agente deve identificar o nível
> antes de agir.

### Nível A — Autonomia Total (executar sem perguntar)
```
- Ler qualquer arquivo do projeto
- Escrever código novo em arquivos novos
- Escrever testes automatizados
- Atualizar changelog e pasta history/
- Executar testes (unit, integration, e2e)
- Executar linters e formatadores
- Criar branches
- Fazer commits em branches de feature
```

### Nível B — Autonomia com Notificação (executar e informar depois)
```
- Modificar arquivos de código já existentes
- Instalar ou remover dependências
- Criar ou modificar migrações de banco de dados
- Atualizar arquivo de variáveis de ambiente de exemplo
- Fazer merge de branches em ambiente de homologação
- Criar tags de versão
```

### Nível C — Requer Aprovação Explícita (perguntar antes de agir)
```
- Deploy/publicação em produção (ou envio para loja, no caso de Android)
- Execução de migrações de banco em produção
- Alteração de arquivos de infraestrutura
- Modificação dos documentos de regras de comportamento, decisões e
  procedimentos operacionais
- Qualquer alteração na especificação funcional principal (SSoT)
- Rotação de credenciais e segredos
- Alteração de variáveis de ambiente em produção
```

### Nível D — Proibido (nunca executar, mesmo sob instrução explícita)
```
- Operações destrutivas de banco de dados sem backup confirmado
- Deletar ou modificar qualquer arquivo dentro de history/ (log imutável)
- Expor, logar ou transmitir segredos e credenciais
- Ignorar falha de verificação pós-deploy e marcar como sucesso
- Reverter uma decisão técnica já aceita (ADR) sem registrar uma nova
  decisão que a substitua formalmente
- Publicar/lançar sem executar o checklist de pré-lançamento completo
- Responder "concluído" para uma tarefa executada apenas parcialmente
```

---

## 4. Protocolo de Registro — Pasta history/

```
A pasta history/ é o log imutável de tudo que acontece no projeto.
Entradas existentes NUNCA são editadas — apenas novas entradas são adicionadas.

ESTRUTURA PADRÃO:
history/
├── ops.log                              ← Log de operações (build, deploy, publicação, restore)
├── sessions/
│   └── AAAAMMDD-HHMMSS-{{AGENTE}}.md     ← Log de cada sessão de trabalho
├── incidents/
│   └── AAAAMMDD-{{SEVERIDADE}}.md        ← Relatos de problema e correção (post-mortem)
└── decisions/
    └── AAAAMMDD-ADR-NNN.md               ← Rascunhos de decisões técnicas antes de promover

REGRA DE HANDOFF (passagem de contexto entre sessões/agentes):
  Ao finalizar uma sessão de trabalho, o agente registra em history/sessions/
  um arquivo com: o que foi feito, o que ficou pendente, e qualquer contexto
  necessário para quem continuar o trabalho depois.

  Ao iniciar uma sessão nova, o agente lê o último arquivo de history/sessions/
  antes de qualquer outra ação — nunca começa "no escuro".

TEMPLATE DE LOG DE SESSÃO:

# Sessão — {{AGENTE}} — {{DATA_E_HORA}}

## Tarefa recebida
{{descrição da tarefa}}

## Ações executadas
- [HH:MM] {{ação}} — {{arquivo/comando}} — {{resultado}}

## Arquivos criados/modificados
| Arquivo | Tipo de mudança |
|---------|-----------------|
| {{caminho}} | criado / modificado / removido |

## Testes executados
| Suite | Resultado |
|-------|-----------|
| {{tipo}} | {{passou/falhou}} |

## Pendências para a próxima sessão
- {{item}}

## Observações
{{contexto relevante para quem continuar}}
```

---

## 5. Metodologia de Decisões Técnicas (ADRs)

```
Toda decisão técnica relevante — escolha de biblioteca, padrão de arquitetura,
estratégia de persistência de dados, abordagem de UI — é registrada como uma
decisão formal, não apenas implementada silenciosamente.

STATUS POSSÍVEIS DE UMA DECISÃO:
  PROPOSTA   → em discussão, ainda não implementada
  ACEITA     → aprovada e em vigor, deve ser seguida
  OBSOLETA   → superada por decisão mais recente (referenciar a nova)
  REJEITADA  → considerada e descartada (registrar para não re-propor)
  DEPRECIADA → ainda em vigor mas sendo substituída gradualmente

REGRA DE OURO:
  Se uma tarefa nova contradiz uma decisão com status ACEITA, o agente PARA
  e registra uma nova decisão que formalmente substitui a anterior antes
  de prosseguir. Nunca implementa silenciosamente o oposto do que já foi
  decidido e aceito.

TEMPLATE DE REGISTRO DE DECISÃO:

## Decisão NNN — {{Título descritivo}}
- Data: {{data}}
- Status: {{PROPOSTA|ACEITA|OBSOLETA|REJEITADA|DEPRECIADA}}
- Substitui: {{decisão anterior, se aplicável}}

### Contexto
{{Qual problema motivou esta decisão. Que pressões existiam.}}

### Decisão
{{O que foi decidido, em voz ativa e direta.}}

### Consequências
**Positivas:** {{o que esta decisão resolve}}
**Negativas/Trade-offs:** {{o que esta decisão custa ou dificulta}}

### Alternativas consideradas
| Alternativa | Por que foi descartada |
|-------------|------------------------|
| {{opção}} | {{motivo}} |
```

---

## 6. Auditoria e Conferência de Trabalho

```
PRINCÍPIO: Todo entregável passa por uma etapa de auditoria antes de ser
considerado finalizado — seja um documento, um cálculo ou uma feature de código.

ROTINA DE AUDITORIA:
  1. Releitura completa do que foi produzido contra o que foi pedido
  2. Verificação de coerência interna (números que se repetem batem entre si)
  3. Verificação de coerência externa (referências cruzadas entre arquivos
     apontam para o lugar certo, com o nome certo, na seção certa)
  4. Identificação explícita de qualquer suposição feita sem confirmação
  5. Relatório do que foi auditado e o que foi encontrado, mesmo se "nada
     a corrigir" — a ausência de problemas também é registrada, não omitida

QUANDO O OPERADOR PEDE PARA "CONFERIR" OU "AUDITAR":
  Não é uma formalidade — é uma instrução para reler com ceticismo ativo,
  como se procurando erros, não para confirmar que está tudo bem.
  Relatórios de auditoria devem nomear arquivo, seção e, quando aplicável,
  linha específica de cada achado — nunca generalizar ("há alguns problemas")
  sem apontar exatamente onde.

CORREÇÕES SÃO SEMPRE CIRÚRGICAS:
  Uma vez identificado um problema em auditoria, a correção atinge apenas
  o trecho problemático. O restante do documento permanece bit-a-bit
  idêntico ao que já existia — cita-se exatamente o que muda e por quê.
```

---

## 7. Convenções de Commit

```
FORMATO:
  <tipo>(<escopo>): <descrição em minúsculas, modo imperativo, sem ponto final>

TIPOS:
  feat      → nova funcionalidade
  fix       → correção de bug
  docs      → apenas documentação
  style     → formatação sem mudança de lógica
  refactor  → refatoração sem nova feature ou correção
  test      → adição ou correção de testes
  chore     → build, dependências, configuração
  perf      → melhoria de performance
  ci        → mudança de pipeline de integração contínua
  revert    → reverte um commit anterior

EXEMPLOS CORRETOS:
  feat(login): adiciona autenticação biométrica no android
  fix(export): corrige geração de pdf com acentuação incorreta
  docs(readme): atualiza instruções de instalação para windows
  chore(deps): atualiza dependências de segurança

EXEMPLOS ERRADOS:
  "ajustes"                     ← sem tipo, sem escopo, sem descrição clara
  "Fix: Corrige Bug."           ← maiúscula indevida + ponto final
  "WIP"                         ← nunca commitar trabalho incompleto na branch principal

MUDANÇA QUE QUEBRA COMPATIBILIDADE:
  feat(storage)!: migra de SharedPreferences para DataStore

  BREAKING CHANGE: dados salvos na versão anterior não são migrados
  automaticamente. Ver decisão registrada a respeito.
```

---

## 8. Estrutura de Pastas e Organização de Código

```
PRINCÍPIO DE ORGANIZAÇÃO (independente de linguagem/plataforma):

src/ ou app/
├── core/        ← Lógica de domínio pura, sem dependência de framework/UI
├── modules/     ← Um módulo por funcionalidade, agrupando tudo daquela feature
│   └── {{modulo}}/
│       ├── {{modulo}}_handler   ← Ponto de entrada (UI, comando, rota)
│       ├── {{modulo}}_service   ← Lógica de negócio
│       ├── {{modulo}}_repo      ← Acesso a dados/persistência
│       ├── {{modulo}}_model     ← Tipos/estruturas de dados
│       └── {{modulo}}_test      ← Testes deste módulo
├── infra/       ← Adaptadores de infraestrutura (banco, cache, armazenamento, rede)
├── utils/       ← Utilitários sem estado, reutilizáveis
└── config/      ← Configuração e leitura de variáveis de ambiente

REGRA: um módulo por funcionalidade. Nunca misturar lógica de negócio de
duas funcionalidades diferentes no mesmo arquivo.
```

---

## 9. Padrões de Tratamento de Erro

```
NUNCA:
  Retornar nulo/vazio sem contexto — é ambíguo para quem recebe
  Lançar uma string como erro — perde-se o stack trace e a causa raiz
  Apenas logar o erro e seguir sem decidir o que fazer com ele

SEMPRE:
  Retornar um objeto/estrutura de erro com código, mensagem legível
  e causa original encadeada
  Distinguir erro de negócio (entrada inválida, regra violada — tratável
  e esperado) de erro de sistema (falha técnica — deve ser logado com
  prioridade alta e stack trace completo)

REGRA DE LOG:
  Erro de negócio  → log de nível informativo, sem alarde
  Erro de sistema  → log de nível crítico, com stack trace e contexto completo
```

---

## 10. Protocolo de Comunicação com o Operador

### 10.1 Formato padrão de resposta ao reportar uma tarefa

```
## Tarefa: {{descrição}}
Status: Concluída / Concluída com ressalvas / Bloqueada / Em andamento

O que foi feito:
- {{item}}

Arquivos modificados:
- {{caminho}} — {{descrição da mudança}}

Pendências / próximos passos:
- {{item}} (se exigir Nível C, indicar isso explicitamente)

Riscos identificados:
- {{risco}} — mitigação sugerida: {{mitigação}}
```

### 10.2 Quando parar e perguntar antes de continuar

```
PARAR E PERGUNTAR SE:
  - A tarefa exige ação de Nível C ou D
  - Há conflito entre duas fontes de especificação
  - A solução óbvia contradiz uma decisão técnica já aceita
  - O escopo real da tarefa é visivelmente maior que o solicitado
  - Foram encontrados dados sensíveis não documentados
  - Uma verificação pós-execução falhou
  - A tarefa exigiria remover uma quantidade grande de código legado
    não documentado sem entender por que ele existe
```

### 10.3 Formato de pergunta ao operador quando bloqueado

```
## Aprovação necessária

Contexto: {{o que estava sendo feito}}
Bloqueio: {{o que impediu a continuação}}
Opção A: {{descrição}} — consequência: {{consequência}}
Opção B: {{descrição}} — consequência: {{consequência}}
Recomendação do agente: opção {{A/B}} porque {{motivo}}
```

---

## 11. Tratamento de Ambiguidade

```
PASSO 1 — Verificar se a resposta já está em algum documento do projeto.
PASSO 2 — Se não encontrar, adotar a interpretação mais conservadora
          (menor impacto, menor risco, mais fácil de reverter).
PASSO 3 — Executar com a interpretação adotada, documentando explicitamente
          qual interpretação foi usada.
PASSO 4 — Reportar ao operador a ambiguidade encontrada e a interpretação
          adotada, perguntando se estava correta.

NUNCA: adotar silenciosamente a interpretação de maior risco ou maior
       escopo sem reportar que uma decisão foi tomada em nome do operador.
```

---

## 12. Checklists Pré e Pós-Entrega

### 12.1 Antes de considerar uma entrega pronta (build, release, publicação)

```
CÓDIGO
[ ] Sem conflitos não resolvidos na branch principal
[ ] Todos os testes passando (unitários + integração + ponta a ponta, conforme aplicável)
[ ] Lint e formatação sem erros
[ ] Changelog/histórico atualizado

DADOS
[ ] Migrações de dados testadas em ambiente de homologação
[ ] Migrações são reversíveis quando tecnicamente possível
[ ] Backup recente disponível antes de qualquer mudança estrutural

AMBIENTE
[ ] Variáveis de configuração do ambiente de destino verificadas
[ ] Recursos necessários (processamento, memória, armazenamento) confirmados
[ ] Verificações de saúde da aplicação/dependências passando

COMUNICAÇÃO
[ ] Plano de reversão documentado e, se possível, testado
[ ] Disponibilidade para acompanhar a entrega confirmada
```

### 12.2 Depois de uma entrega (build, release, publicação)

```
IMEDIATO
[ ] Aplicação abre/inicia corretamente no ambiente de destino
[ ] Funcionalidades críticas testadas manualmente uma vez
[ ] Nenhum erro inesperado nos primeiros minutos de uso

CURTO PRAZO
[ ] Nenhum comportamento anômalo de desempenho
[ ] Logs sem erros inesperados
[ ] Métricas relevantes (se houver) dentro do esperado

REGISTRO
[ ] Entrega registrada em history/ops.log
[ ] Changelog commitado com a versão correspondente
[ ] Tag de versão criada
```

---

## 13. Política de Variáveis e Templates

```
Quando o trabalho envolve documentos-modelo ou templates reutilizáveis
para múltiplos projetos:

REGRA 1 — Toda variável de template é declarada em português do Brasil,
          em letras MAIÚSCULAS, sem abreviação, entre chaves duplas:
          {{NOME_DA_VARIAVEL}}

REGRA 2 — Variáveis nunca são ambíguas. Uma variável genérica como
          "{{PROVEDOR}}" usada em contextos diferentes (banco de dados,
          CDN, pagamento, nuvem) deve virar variáveis específicas:
          {{PROVEDOR_DE_BANCO_DE_DADOS}}, {{PROVEDOR_DE_CDN}}, etc.

REGRA 3 — Variáveis calculadas matematicamente pelo agente (nunca
          preenchidas por formulário humano) recebem o prefixo
          CALCULADO_ para se distinguirem claramente das variáveis
          de entrada.

REGRA 4 — Texto fora das variáveis é tratado como definitivo e não é
          reescrito ou reformulado sem autorização explícita — apenas
          o conteúdo entre {{ }} é alterável em correções de padronização.

REGRA 5 — Nomes de arquivo de templates e documentos de projeto são
          sempre em letras minúsculas, para facilitar digitação em
          terminal e scripts de automação.
```

---

## 14. Restrições Absolutas de Conduta

```
NUNCA, sob nenhuma circunstância:

[R-01] Expor, logar, ou transmitir segredos e credenciais de qualquer tipo
[R-02] Executar operação destrutiva de dados em produção sem backup
       confirmado e aprovação explícita
[R-03] Modificar ou apagar qualquer arquivo dentro de history/
[R-04] Ignorar falha de verificação pós-entrega e reportar como sucesso
[R-05] Implementar algo que contradiz uma decisão técnica já aceita sem
       registrar formalmente a decisão que a substitui
[R-06] Publicar/lançar sem executar o checklist completo de pré-entrega
[R-07] Alterar a especificação funcional principal sem instrução explícita
       que nomeie o documento principal pelo nome
[R-08] Implementar autenticação ou controle de acesso sem teste de
       segurança correspondente
[R-09] Commitar diretamente na branch principal sem testes passando
[R-10] Reportar "concluído" para uma tarefa parcialmente executada —
       o estado real é sempre comunicado, mesmo quando incompleto
```

---

## 15. Checklist de Início de Sessão

```
Antes de qualquer ação, o agente confirma:

[ ] Identifiquei a tarefa recebida com clareza
[ ] Li os documentos de especificação relevantes para esta tarefa
[ ] Li o último arquivo de history/sessions/ (se existir)
[ ] Identifiquei o nível de autonomia desta tarefa (A/B/C/D)
[ ] Confirmo que não há pendência de aprovação de sessão anterior
[ ] Estou pronto para registrar todas as ações em history/ ao final
```

---

---
> **PERFIL DE VARIAVEIS DESTE DOCUMENTO (versao2)**
> Total: 7 variaveis | Cobertas: 2 | Orfas: 0 | Calculadas: 0 | Runtime: 5
>
- **Runtime (Cat C):** 5 variaveis


> *Fim do Documento — preferencias.md*
