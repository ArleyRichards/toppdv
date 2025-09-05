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