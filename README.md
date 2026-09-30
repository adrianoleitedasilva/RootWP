# Root WP (tema WordPress)

Tema clássico para site educacional com blog de artigos sobre tecnologia e educação.
Autor: Adriano Silva — https://adrianosilva.pro.br

## Instalação
1. Compacte a pasta `rootwp` em `rootwp.zip` e envie em **Aparência > Temas > Adicionar novo > Enviar tema**. Depois, ative o tema.
2. Crie duas páginas: **Início** (pode ficar vazia) e **Blog** (vazia).
3. Em **Configurações > Leitura**, escolha "Uma página estática": Página inicial = Início, Página de posts = Blog.
4. Em **Aparência > Menus**, crie o menu e atribua a "Menu principal" (e opcionalmente "Menu do rodapé").
5. Configure o tema no menu **Root WP** do painel.
6. Em **Aparência > Widgets**, monte a barra lateral e as colunas do rodapé com os widgets "Root WP – …".

## Painel do tema (menu "Root WP")
- **Logo e favicon**: logo em texto (JetBrains Mono) ou imagem, descrição do site, favicon.
- **Página inicial**: destaque (textos, imagem, botões), ordem das seções, artigos recentes e "Explore por tema"
  (automático ou com categorias escolhidas e ordenadas).
- **Aparência**: modo escuro (sistema/claro/escuro, botão), fonte e largura do artigo, barra lateral.
- **Cores**: paletas prontas (Azul, Verde, Roxo, Laranja, Grafite), cores dos modos claro e escuro, aviso de
  contraste e prévia ao vivo. Bordas, texto secundário, tons suaves e a cor do texto sobre botões são calculados.
- **Cabeçalho**: cabeçalho fixo, botão de busca, botão de destaque (CTA).
- **Artigos**: sumário, tempo de leitura, barra de progresso, tempo restante ("faltam 3 min"), botões A− A A+
  para o visitante ajustar o texto, botão "Copiar" nos blocos de código, séries de artigos, compartilhamento,
  caixa do autor, posts relacionados e aviso em posts antigos.
- **Avisos**: barra de aviso no topo (com link, cor e botão de fechar) e chamada no fim dos artigos (com imagem e
  categorias excluídas).
- **SEO e integrações**: descrição e prévia de compartilhamento (Open Graph / X), imagem padrão, Google Analytics 4 ou
  Tag Manager e códigos extras no head/body. Se um plugin de SEO estiver ativo, o tema não gera as meta tags.
- **Ferramentas**: exportar/importar configurações (.json, inclui ajustes das categorias) e restaurar padrões.
- **Rodapé**: bloco "Sobre", redes sociais (com a cor de cada rede ao passar o mouse), copyright, menu e "voltar ao topo".

## Categorias e templates de post
- Em **Posts > Categorias > editar**, cada categoria define: layout da página (Grade, Lista, Revista ou Compacta),
  template padrão dos posts, cor (bubbles, cartões de tema e topo da página) e imagem de capa.
- Templates de post: Padrão, Leitura focada, Capa em tela cheia, Tutorial e Vídeo. No editor, "Modelo" troca o
  template só daquele post. Prioridade: modelo do post > template da categoria > padrão.
- Tutorial: caixa "Tutorial" no editor (nível e tempo estimado); os H2 viram passos numerados.
- Vídeo: o primeiro bloco de vídeo (YouTube, Vimeo ou arquivo) vira destaque no topo.

## Séries de artigos
Em **Posts > Séries**, crie a série e marque-a no editor de cada post (caixa "Séries"). A ordem das partes é a data de
publicação. O post mostra "Série · Parte 2 de 5" com a lista de partes, e no fim leva à parte anterior e à próxima.
A página da série (/serie/nome/) lista as partes em ordem, e os cards ganham o selo "série · parte N".

## Widgets do tema
Busca, Posts recentes, Comentários recentes, Categorias, Arquivos, Vídeo (YouTube ou biblioteca de mídia)
e Spotify. Vídeo e Spotify carregam o player apenas quando o visitante clica.

## Recursos
- Tipografia: JetBrains Mono em títulos e elementos de destaque; Google Sans no corpo do texto.
  Fontes hospedadas no próprio tema (assets/fonts, licença SIL OFL 1.1): nenhuma requisição ao Google.
- Página inicial com destaque, artigos recentes e grade de temas (categorias)
- Blog em cards, com o artigo mais recente em destaque
- Modo escuro, estilos para blocos de código, tabelas e citações
- Caixas didáticas: no editor, selecione um bloco Grupo ou Parágrafo e escolha o estilo **Dica**, **Atenção** ou **Importante**
- O "Resumo" do post aparece como subtítulo no artigo
- A bio do autor vem de **Usuários > Perfil > Informações biográficas**

## Estrutura
```
style.css               estilos + cabeçalho do tema
functions.php           configuração, fontes, scripts, favicon, CSS vindo do painel
inc/admin.php           painel do tema (menu "Root WP")
inc/widgets.php         widgets do tema
inc/template-tags.php   logo, metadados, tempo de leitura, breadcrumbs, compartilhar, autor, relacionados
inc/toc.php             sumário automático
inc/home.php            seções da página inicial
inc/categories.php      layouts por categoria, templates de post, caixa Tutorial
inc/colors.php          paletas, cálculo de cores e contraste
inc/comments.php        comentários (balão, selo de autor, respostas) e formulário
inc/discovery.php       perfil do autor, busca (destaque, filtros) e sugestões da 404
inc/seo.php             meta tags, Open Graph, Analytics e códigos extras
inc/notices.php         barra de aviso e chamada no fim dos artigos
inc/tools.php           exportar, importar e restaurar padrões
inc/reading.php         séries, tamanho do texto (A− A A+) e tempo restante
author.php              página do autor (perfil)
category.php            página de categoria (4 layouts)
templates/post-*.php    modelos de post (aparecem em "Modelo" no editor)
inc/customizer.php      valores antigos do Personalizar (migração)
front-page.php          página inicial
index.php               listagem do blog
single.php              artigo
page.php / archive.php / search.php / 404.php
template-parts/         card, item de lista e "nada encontrado"
assets/js/main.js       menu, busca, modo escuro, progresso, sumário, copiar link, players
assets/js/admin.js      painel do tema (mídia, cor)
assets/js/widgets-admin.js  seletor de mídia nos widgets
assets/css/editor.css   aparência no editor de blocos
assets/css/admin.css    visual do painel do tema
assets/css/fonts.css    @font-face das fontes locais
assets/fonts/           fontes (woff2) e licenças OFL
languages/rootwp.pot    modelo de tradução (513 textos)
screenshot.png          imagem do tema em Aparência > Temas
```

## Tradução
O tema é escrito em português e está pronto para tradução (text domain `rootwp`). Use o Poedit ou o
Loco Translate com `languages/rootwp.pot` e salve `rootwp-xx_XX.mo` em `languages/`.

## Desenvolvimento
Código com prefixo `rootwp_` (funções), `Rootwp_` (classes), `ROOTWP_` (constantes) e text domain `rootwp`.
As opções do painel ficam na opção `rootwp_options` do banco.
