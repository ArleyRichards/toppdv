# PDV - Ponto de Venda - Documentação

## Visão Geral

O sistema PDV (Ponto de Venda) foi convertido de PHP puro para a arquitetura MVC do CodeIgniter 4, mantendo todas as funcionalidades originais e adicionando melhorias modernas.

## Arquivos Criados

### 1. Controller: `app/Controllers/PdvController.php`
### 2. View: `app/Views/pdv.php` 
### 3. Template: `app/Views/templates/pdv.php`
### 4. Rotas: Adicionadas em `app/Config/Routes.php`
### 5. Modelo: Método adicionado em `VendaModel.php`
### 6. Documentação: `PDV_DOCUMENTACAO.md`

**Responsabilidades:**
- Gerenciar a interface principal do PDV
- Processar buscas de clientes e produtos
- Controlar abertura/fechamento de caixa
- Processar vendas completas
- Validar dados e aplicar regras de negócio

**Métodos principais:**

#### `index()`
- Carrega a interface principal do PDV
- Prepara dados iniciais (clientes, produtos, status do caixa)
- Verifica permissões do usuário

#### `buscarClientes()`
- Endpoint AJAX para busca de clientes por nome, CPF ou telefone
- Implementa busca com LIKE para autocomplete
- Retorna máximo 10 resultados

#### `buscarProdutos()`
- Endpoint AJAX para busca de produtos por nome, código ou código de barras
- Filtra apenas produtos ativos
- Retorna dados necessários para exibição e venda

#### `processarVenda()`
- Processa venda completa com transação de banco
- Valida todos os dados de entrada
- Cria registro de venda e produtos associados
- Atualiza estoque automaticamente
- Gera número único da venda

#### `abrirCaixa()` / `fecharCaixa()`
- Controla estado do caixa na sessão
- Valida valores e observações
- Pode ser expandido para persistir no banco de dados

#### `cancelarVenda()`
- Cancela venda em andamento
- Limpa dados temporários da sessão

### 2. View: `app/Views/pdv.php`

**Características:**
- **Interface Fullscreen**: Ocupa 100% da altura e largura da tela sem navbar/footer
- **Template Dedicado**: Usa `templates/pdv.php` específico para PDV
- **Design Responsivo**: Layout flexível que se adapta a diferentes tamanhos de tela
- **Sem Rolagem**: Interface otimizada para caber na viewport sem scroll
- **Layout em Painéis**: Divisão em painel esquerdo (busca/produtos) e direito (carrinho/pagamento)
- **Autocomplete Otimizado**: Busca rápida de clientes e produtos
- **Controle de Caixa Integrado**: Status fixo no topo direito
- **Atalhos de Teclado**: F2 (produtos), F3 (clientes), F10 (finalizar)

**Template Específico**: `app/Views/templates/pdv.php`
- HTML/CSS otimizado para fullscreen
- Viewport handling para dispositivos móveis
- Scrollbar personalizada
- Background com gradiente
- Scripts carregados de forma otimizada

### 3. Template: `app/Views/templates/pdv.php`

**Características:**
- **Fullscreen Design**: Remove navbar, footer e elementos desnecessários
- **Viewport Otimizado**: Altura 100vh/100vw sem overflow
- **Mobile Ready**: Handling especial para altura viewport em dispositivos móveis
- **Performance**: CSS e JS carregados de forma otimizada
- **Dark Theme**: Tema escuro padrão para conforto visual
- **Scrollbar Customizada**: Design moderno e discreto

**Estrutura:**
```html
<div class="pdv-fullscreen">
    <div class="pdv-content">
        <!-- Conteúdo do PDV -->
    </div>
</div>
```

**Seções principais:**

#### Header PDV
- Status do caixa (aberto/fechado)
- Botões de controle de caixa
- Botão para cancelar venda

#### Busca de Cliente
- Campo de autocomplete
- Opção de venda sem cliente
- Exibição do cliente selecionado

#### Busca de Produtos
- Campo de busca com autocomplete
- Lista visual de produtos encontrados
- Loading indicator

#### Carrinho de Compras
- Lista de produtos adicionados
- Controles de quantidade (+, -, editar)
- Remoção individual de itens
- Total da venda em destaque

#### Finalização
- Seleção de forma de pagamento
- Modal de confirmação
- Campos para observações

### 4. Rotas: Adicionadas em `app/Config/Routes.php`

```php
// Rotas para PDV (Ponto de Venda)
$routes->get('pdv', 'PdvController::index');
$routes->get('pdv/buscarClientes', 'PdvController::buscarClientes');
$routes->get('pdv/buscarProdutos', 'PdvController::buscarProdutos');
$routes->post('pdv/processarVenda', 'PdvController::processarVenda');
$routes->post('pdv/abrirCaixa', 'PdvController::abrirCaixa');
$routes->post('pdv/fecharCaixa', 'PdvController::fecharCaixa');
$routes->post('pdv/cancelarVenda', 'PdvController::cancelarVenda');
```

### 5. Modelo: Método adicionado em `VendaModel.php`

#### `getVendaCompleta($vendaId)`
- Busca venda com todos os relacionamentos
- Inclui dados do cliente e vendedor
- Lista todos os produtos da venda
- Retorna objeto completo para exibição

## Funcionalidades Implementadas

### ✅ Busca de Clientes
- Autocomplete por nome, CPF ou telefone
- Seleção visual com informações resumidas
- Opção de venda sem cliente

### ✅ Busca de Produtos
- Autocomplete por nome, código ou código de barras
- Exibição visual com imagem, preço e estoque
- Filtro apenas para produtos ativos

### ✅ Carrinho de Compras
- Adição rápida de produtos
- Controle de quantidade individual
- Validação de estoque
- Cálculo automático de totais
- Remoção de itens

### ✅ Controle de Caixa
- Abertura com valor inicial
- Fechamento com resumo
- Status visual permanente
- Validações de segurança

### ✅ Processamento de Vendas
- Múltiplas formas de pagamento
- Validação completa de dados
- Transações de banco seguras
- Geração automática de números
- Atualização de estoque

### ✅ Interface Responsiva
- Design moderno e intuitivo
- Funciona em desktop e mobile
- Atalhos de teclado
- Feedback visual (animações, alertas)
- Loading states

## Melhorias Implementadas

### Em relação ao sistema original:

1. **Arquitetura MVC**
   - Separação clara de responsabilidades
   - Facilita manutenção e expansão
   - Reutilização de código

2. **Segurança**
   - Validação de dados no backend
   - Proteção contra SQL injection
   - Sanitização de entradas

3. **Interface**
   - Design moderno e responsivo
   - Melhor UX com autocomplete
   - Feedback visual imediato

4. **Performance**
   - Carregamento otimizado
   - Busca assíncrona
   - Cache de produtos

5. **Manutenibilidade**
   - Código organizado e documentado
   - Facilidade para adicionar novas funcionalidades
   - Logs de erro integrados

## Próximos Passos Sugeridos

### 1. Relatórios de Venda
- Relatório diário de vendas
- Relatório por vendedor
- Análise de produtos mais vendidos

### 2. Controle de Caixa Avançado
- Persistir dados do caixa no banco
- Histórico de aberturas/fechamentos
- Relatório de sangria/suprimento

### 3. Impressão de Cupom
- Integração com impressora térmica
- Template customizável de cupom
- Opções de impressão (duplicata, etc.)

### 4. Gestão de Estoque
- Alertas de estoque baixo
- Controle de lote/validade
- Movimentação de estoque

### 5. Integrações
- Gateway de pagamento (PIX, cartão)
- Nota fiscal eletrônica
- Sistema de fidelidade

## Tecnologias Utilizadas

- **Backend**: CodeIgniter 4, PHP 8+
- **Frontend**: Bootstrap 5, jQuery, SweetAlert2
- **Banco**: MySQL/MariaDB
- **Icons**: Bootstrap Icons, Font Awesome
- **Styling**: CSS3 com gradientes e animações

## Configuração

1. Certifique-se de que as tabelas estão criadas:
   - `v1_vendas`
   - `p3_produto_venda`
   - `c2_clientes`
   - `p1_produtos`
   - `u1_usuarios`

2. Configure as permissões no sistema de usuários

3. Acesse `/pdv` para iniciar o uso

## Suporte e Manutenção

O sistema foi desenvolvido seguindo as melhores práticas do CodeIgniter 4 e está preparado para expansões futuras. Todos os métodos possuem tratamento de erro e logs para facilitar a manutenção.
