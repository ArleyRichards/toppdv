<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

// Rotas de configuração (apenas admin)
$routes->get('admin/configuracoes', 'ConfiguracaoController::index');
$routes->post('admin/configuracoes/salvar', 'ConfiguracaoController::salvar');
