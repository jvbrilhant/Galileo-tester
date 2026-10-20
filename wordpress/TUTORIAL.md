# Camp Hero no WordPress + Elementor Pro — tutorial

O hero do acampamento (fogueira + 3 barracas) vem em um **plugin** chamado **Camp Hero**. Ele adiciona ao Elementor um widget próprio, também chamado **Camp Hero**: você arrasta o widget para a página e edita textos e links no painel lateral, como qualquer outro widget.

## Por que plugin e não colar HTML no Elementor?

| Método | Problema |
|---|---|
| Widget HTML com o código colado | Você teria que subir cerca de 25 imagens na Biblioteca de Mídia e trocar cada URL no código. Os textos ficam escondidos dentro do código e qualquer atualização do Elementor ou do tema pode quebrar o visual. |
| Template JSON sozinho | Um template JSON não carrega imagens, CSS nem animações. Sozinho, ele não funciona. |
| **Plugin com widget (recomendado)** | Imagens, fontes, CSS e animação ficam dentro do plugin. Os textos e links são editados no Elementor. O CSS só carrega nas páginas que usam o widget. |

## O que você recebe

- `gc-camp-hero.zip`: o plugin, que você instala no WordPress.
- `camp-hero-template.json` (opcional): um template do Elementor com o hero já montado num container de largura total.

## Passo a passo

### 1. Instalar o plugin

1. No painel do WordPress, vá em **Plugins → Adicionar novo → Enviar plugin**.
2. Escolha o arquivo `gc-camp-hero.zip`, clique em **Instalar agora** e depois em **Ativar**.

### 2. Colocar o hero na página

Use uma das duas opções.

**Opção A: arrastar o widget (mais simples)**

1. Abra a página no Elementor (**Editar com Elementor**).
2. Adicione um **Container** no topo da página e, na aba **Layout**, configure:
   - **Largura do conteúdo:** *Largura total* (Full Width).
   - **Espaço entre elementos (Gap):** 0.
   - Na aba **Avançado**, deixe **Padding** e **Margem** em 0.
3. No painel de widgets, busque **Camp Hero** e arraste para dentro do container.

**Opção B: importar o template pronto**

1. Vá em **Modelos → Modelos salvos → Importar modelos** e envie o arquivo `camp-hero-template.json`.
2. No editor da página, clique no ícone de pasta (**Adicionar modelo**), abra a aba **Meus modelos** e insira **Camp Hero**.

### 3. Ajustar ao seu cabeçalho

No painel do widget, abra **Texto inicial** e configure os dois campos abaixo.

- **Altura do cabeçalho do site (px):** a altura do seu header. Para conferir, inspecione o header no navegador.
- **Cabeçalho transparente por cima do hero:** depende de como o header fica na página.

| Seu header | Configure |
|---|---|
| Fica **acima** do hero, com fundo próprio | Altura = altura do header (ex.: 80) e o switch em **Não**. O hero encolhe para caber na tela abaixo do header. |
| É **transparente e fica por cima** do hero (como no site atual) | Altura = altura do header e o switch em **Sim**. O título desce para não ficar atrás do menu. |
| Não há header nessa página | Deixe a altura em 0. |

Para deixar o header do Elementor Pro transparente por cima do hero, faça assim:

1. Vá em **Modelos → Criador de temas → Cabeçalho**.
2. No container do header, deixe o fundo transparente.
3. Em **Avançado → Movimento / Posição**, defina a posição como *Absoluta*, com largura total e `z-index` alto (ex.: 99).

### 4. Editar textos e links

No painel do widget, cada parte tem sua seção:

- **Texto inicial:**
  - linha de destaque (texto pequeno em amarelo);
  - título;
  - tag do título.
- **Fogueira — Kitchen Ideas**, **Barraca azul — Library**, **Barraca bege — Study Groups**, **Barraca laranja — About Us:** cada uma tem nome, descrição e link. A descrição aparece ao passar o mouse. O link aceita conteúdo dinâmico do Elementor Pro.

Os links já vêm preenchidos com `/kitchen-ideas/`, `/library/`, `/study-groups/` e `/about/`.

### 5. Estilo dos botões

Na aba **Estilo → Botões** você ajusta as plaquinhas com os nomes que ficam embaixo da fogueira e das barracas. No celular, os mesmos ajustes valem para os cartões.

- **Cor de fundo** e **Cor de fundo no hover**.
- **Opacidade do fundo**: vai de 0 (transparente) a 1 (sólido). Só o fundo fica transparente; o texto continua legível.
- **Opacidade do fundo da descrição**: o mesmo, para a caixinha que aparece no hover.
- **Borda**: estilo (sólida, tracejada, pontilhada, dupla ou sem borda), espessura, cor e cor no hover.
- **Arredondamento** dos cantos.
- **Espaçamento interno**: o padding dos botões, ajustável por dispositivo.

Se o fundo ficar muito transparente sobre partes claras da foto, o texto pode perder contraste. Abaixo de 0,6, confira no navegador.

### 5b. Trocar fontes e cores

Na aba **Estilo** do widget há quatro seções, uma para cada tipo de texto:

- **Linha de destaque**
- **Título**
- **Nomes (Library, Study Groups…)**
- **Descrições (hover)**

Em cada seção, use **Tipografia** para escolher fonte, tamanho (por dispositivo), peso, espaçamento etc. As fontes do Google ou as fontes globais do seu Kit carregam automaticamente. Use **Cor** para mudar a cor do texto. Os nomes têm também **Cor no hover**; se você não definir uma, eles continuam ficando âmbar ao passar o mouse.

Campos deixados em branco mantêm o visual original (Prata nos títulos e Poppins no resto).

### 6. SEO: tag do título

O título do hero vem como **H1**. Se a página já tiver outro H1, como o título do tema, mude a **Tag do título** para **H2** ou desative o título da página: em **Configurações da página → Ocultar título**, ou use o modelo de página **Elementor Largura Total**.

### 7. Publicar e testar

Publique a página e confira:

- **Desktop:** a cena aparece inteira na tela; ao passar o mouse, a barraca acende e a descrição aparece.
- **Celular:** a fogueira fica em cima e as três barracas aparecem como cartões.
- **Plugins de cache e otimização** (WP Rocket, LiteSpeed etc.): limpe o cache depois de instalar. Se a cena aparecer quebrada, exclua `camp-hero.css` e `camp-hero.js` da combinação de arquivos (combine/minify).

## Alternativa sem Elementor: shortcode

Em qualquer lugar que aceite shortcode (inclusive o widget **Shortcode** do Elementor), use:

```
[gc_camp_hero]
[gc_camp_hero header_height="80" header_overlay="yes"]
[gc_camp_hero eyebrow="Texto pequeno" title="Seu título" heading_tag="h2"]
```

Pelo shortcode dá para mudar a linha de destaque, o título, a tag e o cabeçalho. Nomes, descrições e links das barracas só podem ser editados pelo widget.

## Perguntas frequentes

- **Preciso das fontes Prata e Poppins no site?** Não. O plugin já traz as duas, hospedadas localmente. Para usar outras fontes, veja o passo 5.
- **Funciona com qualquer tema?** Sim. Todo o CSS do hero é isolado sob `.gc-hero`. Testei com o tema **Hello Elementor**.
- **Posso trocar as imagens?** Sim. Substitua os arquivos em `wp-content/plugins/gc-camp-hero/assets/img/`, mantendo os mesmos nomes e proporções. As posições das barracas ficam em `includes/render.php`, na lista `$tents`; os valores são frações da foto de fundo.
- **Animações e acessibilidade:** as animações param quando o hero sai da tela ou a aba fica oculta, e também com "reduzir movimento" ativado no sistema. Os links funcionam pelo teclado.

## Requisitos

- WordPress 6.0 ou superior.
- PHP 7.4 ou superior.
- Elementor 3.x com containers ativados. Desde o Elementor 3.16 eles vêm ativados por padrão. Se a sua instalação ainda usa Seções e Colunas, use uma seção de largura total sem padding.
