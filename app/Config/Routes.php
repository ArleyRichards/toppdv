<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/home', 'Home::index');

// Rotas de configuração (apenas admin)
$routes->get('admin/configuracoes', 'ConfiguracaoController::index');
$routes->post('admin/configuracoes/salvar', 'ConfiguracaoController::salvar');

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