<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->get('/logout', 'AuthController::logout');

// Rotas de autenticação (login, logout, recuperar/redefinir senha)
$routes->post('auth/login', 'AuthController::processLogin');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('auth/recuperar-senha', 'AuthController::recuperarSenha');
$routes->post('auth/recuperar-senha', 'AuthController::processRecuperarSenha');
$routes->get('auth/redefinir-senha/(:any)', 'AuthController::redefinirSenha/$1');
$routes->post('auth/redefinir-senha', 'AuthController::processRedefinirSenha');
$routes->get('acesso-negado', 'AuthController::acessoNegado');

$routes->get('/home', 'Home::index');
$routes->get('relatorios', 'RelatoriosController::index');
$routes->get('relatorios/vendas/pdf', 'RelatoriosController::vendasPdf');
// Additional relatorios PDF endpoints
$routes->get('relatorios/ordens/pdf', 'RelatoriosController::ordensPdf');
$routes->get('relatorios/comissoes/pdf', 'RelatoriosController::comissoesPdf');
$routes->get('relatorios/livrocaixa/pdf', 'RelatoriosController::livroCaixaPdf');
// Licença
$routes->get('licenca', 'LicencaController::index');

// Perfil do usuário
$routes->get('perfil', 'PerfilController::index');
$routes->post('perfil/salvar', 'PerfilController::salvar');

// Rotas de configuração (apenas admin)
$routes->get('configuracoes', 'ConfiguracaoController::index');
$routes->post('configuracoes/salvar', 'ConfiguracaoController::salvar');

// Rotas para gerenciamento de clientes
$routes->get('clientes', 'ClienteController::index');
$routes->get('clientes/estatisticas', 'ClienteController::estatisticas');
$routes->get('clientes/buscar', 'ClienteController::buscar');
$routes->get('clientes/cep', 'ClienteController::consultarCep');
$routes->get('clientes/list', 'ClienteController::list');
$routes->resource('clientes', [
    'controller' => 'ClienteController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de produtos
$routes->get('produtos', 'ProdutoController::index');
$routes->get('produtos/estatisticas', 'ProdutoController::estatisticas');
$routes->get('produtos/buscar', 'ProdutoController::buscar');
$routes->get('produtos/cep', 'ProdutoController::consultarCep');
$routes->get('produtos/list', 'ProdutoController::list');
$routes->resource('produtos', [
    'controller' => 'ProdutoController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de servicos
$routes->get('servico', 'ServicoController::index');
$routes->get('servico/estatisticas', 'ServicoController::estatisticas');
$routes->get('servico/buscar', 'ServicoController::buscar');
$routes->get('servico/cep', 'ServicoController::consultarCep');
$routes->get('servico/list', 'ServicoController::list');
$routes->resource('servico', [
    'controller' => 'ServicoController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de categorias
$routes->get('categorias', 'CategoriaController::index');
$routes->get('categorias/estatisticas', 'CategoriaController::estatisticas');
$routes->get('categorias/buscar', 'CategoriaController::buscar');
$routes->get('categorias/cep', 'CategoriaController::consultarCep');
$routes->get('categorias/list', 'CategoriaController::list');
$routes->resource('categorias', [
    'controller' => 'CategoriaController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de garantias
$routes->get('garantias', 'GarantiaController::index');
$routes->get('garantias/estatisticas', 'GarantiaController::estatisticas');
$routes->get('garantias/buscar', 'GarantiaController::buscar');
$routes->get('garantias/cep', 'GarantiaController::consultarCep');
$routes->get('garantias/list', 'GarantiaController::list');
$routes->resource('garantias', [
    'controller' => 'GarantiaController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de fornecedores
$routes->get('fornecedores', 'FornecedorController::index');
$routes->get('fornecedores/estatisticas', 'FornecedorController::estatisticas');
$routes->get('fornecedores/buscar', 'FornecedorController::buscar');
$routes->get('fornecedores/cep', 'FornecedorController::consultarCep');
$routes->get('fornecedores/list', 'FornecedorController::list');
$routes->resource('fornecedores', [
    'controller' => 'FornecedorController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de usuarios
$routes->get('usuarios', 'UsuarioController::index');
$routes->get('usuarios/estatisticas', 'UsuarioController::estatisticas');
$routes->get('usuarios/buscar', 'UsuarioController::buscar');
$routes->get('usuarios/cep', 'UsuarioController::consultarCep');
$routes->get('usuarios/list', 'UsuarioController::list');
// Allow POST with X-HTTP-Method-Override for update (some servers block PUT)
$routes->post('usuarios/(:num)', 'UsuarioController::update/$1');
$routes->resource('usuarios', [
    'controller' => 'UsuarioController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de tecnicos
$routes->get('tecnicos', 'TecnicoController::index');
$routes->get('tecnicos/estatisticas', 'TecnicoController::estatisticas');
$routes->get('tecnicos/buscar', 'TecnicoController::buscar');
$routes->get('tecnicos/cep', 'TecnicoController::consultarCep');
$routes->get('tecnicos/list', 'TecnicoController::list');
// Allow POST with X-HTTP-Method-Override for update (some servers block PUT)
$routes->post('tecnicos/(:num)', 'TecnicoController::update/$1');
$routes->resource('tecnicos', [
    'controller' => 'TecnicoController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de ordens de servico
$routes->get('ordens', 'OrdemController::index');
$routes->get('ordens/estatisticas', 'OrdemController::estatisticas');
$routes->get('ordens/buscar', 'OrdemController::buscar');
$routes->get('ordens/cep', 'OrdemController::consultarCep');
$routes->get('ordens/list', 'OrdemController::list');
$routes->post('ordens/(:num)/operacoes', 'OrdemController::saveOperacoes/$1');
// Allow POST with X-HTTP-Method-Override for update (some servers block PUT)
$routes->post('ordens/(:num)', 'OrdemController::update/$1');
$routes->get('ordens/gerarCupom/(:any)', 'OrdemController::gerarCupom/$1');
$routes->get('ordens/downloadCupom/(:any)', 'OrdemController::downloadCupom/$1');
// Rota para faturamento de ordem via POST
$routes->post('ordens/(:num)/faturar', 'OrdemController::faturar/$1');
$routes->resource('ordens', [
    'controller' => 'OrdemController',
    'except' => ['new', 'edit']
]);

// Rotas para gerenciamento de vendas
$routes->get('vendas', 'VendaController::index');
$routes->get('vendas/estatisticas', 'VendaController::estatisticas');
$routes->get('vendas/buscar', 'VendaController::buscar');
$routes->get('vendas/cep', 'VendaController::consultarCep');
$routes->get('vendas/list', 'VendaController::list');
$routes->post('vendas/(:num)/operacoes', 'VendaController::saveOperacoes/$1');
// Rota para faturamento de venda via POST
$routes->post('vendas/(:num)/faturar', 'VendaController::faturar/$1');
// Fallbacks: aceitar também formatos onde a id é concatenada sem '/' (ex: vendas13/produtos)
// Esses padrões usam regex para capturar o número logo após 'vendas'
$routes->post('vendas(\d+)/operacoes', 'VendaController::saveOperacoes/$1');
$routes->post('vendas(\d+)/produtos', 'VendaController::saveOperacoes/$1');
// Alias para compatibilidade com o frontend que posta em /vendas/{id}/produtos
$routes->post('vendas/(:num)/produtos', 'VendaController::saveOperacoes/$1');
// Padrão controller/metodo/parametro solicitado: vendas/add_produtos/13
$routes->post('vendas/add_produtos/(:num)', 'VendaController::saveOperacoes/$1');
// Allow POST with X-HTTP-Method-Override for update (some servers block PUT)
$routes->post('vendas/(:num)', 'VendaController::update/$1');
// Fallback to allow deleting via POST for environments that block DELETE
$routes->post('vendas/(:num)/delete', 'VendaController::delete/$1');
$routes->resource('vendas', [
    'controller' => 'VendaController',
    'except' => ['new', 'edit']
]);

// Rotas para PDV (Ponto de Venda)
$routes->get('pdv', 'PdvController::index');
$routes->get('pdv/buscarClientes', 'PdvController::buscarClientes');
$routes->get('pdv/buscarProdutos', 'PdvController::buscarProdutos');
$routes->post('pdv/processarVenda', 'PdvController::processarVenda');
$routes->post('pdv/abrirCaixa', 'PdvController::abrirCaixa');
$routes->post('pdv/fecharCaixa', 'PdvController::fecharCaixa');
$routes->post('pdv/sangriaCaixa', 'PdvController::sangriaCaixa');
$routes->post('pdv/suprimentoCaixa', 'PdvController::suprimentoCaixa');
$routes->post('pdv/cancelarVenda', 'PdvController::cancelarVenda');
$routes->get('pdv/gerarCupom/(:num)', 'PdvController::gerarCupom/$1');
$routes->get('pdv/downloadCupom/(:num)', 'PdvController::downloadCupom/$1');