<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Autenticação
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Admin - Spots
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], static function ($routes) {
    $routes->get('spots', 'Spots::index');
    $routes->match(['get', 'post'], 'spots/create', 'Spots::create');
    $routes->match(['get', 'post'], 'spots/edit/(:num)', 'Spots::edit/$1');
    $routes->get('spots/delete/(:num)', 'Spots::delete/$1');

    // Produtos do spot
    $routes->get('spots/(:num)/produtos', 'SpotProdutos::index/$1');
    $routes->match(['get', 'post'], 'spots/(:num)/produtos/create', 'SpotProdutos::create/$1');
    $routes->match(['get', 'post'], 'spots/(:num)/produtos/edit/(:num)', 'SpotProdutos::edit/$1/$2');
    $routes->get('spots/(:num)/produtos/delete/(:num)', 'SpotProdutos::delete/$1/$2');

    // Serviços do spot (itens dedicados, além do texto geral)
    $routes->get('spots/(:num)/servicos', 'SpotServicos::index/$1');
    $routes->match(['get', 'post'], 'spots/(:num)/servicos/create', 'SpotServicos::create/$1');
    $routes->match(['get', 'post'], 'spots/(:num)/servicos/edit/(:num)', 'SpotServicos::edit/$1/$2');
    $routes->get('spots/(:num)/servicos/delete/(:num)', 'SpotServicos::delete/$1/$2');
});

// Página pública do spot
$routes->get('spot/(:segment)', 'Spot::view/$1');
// Página de serviços do spot
$routes->get('spot/(:segment)/servicos', 'Spot::servicos/$1');
// Página de produtos do spot
$routes->get('spot/(:segment)/produtos', 'Spot::produtos/$1');

// Rota de teste de banco de dados
$routes->get('debug/db', 'Debug::db');
