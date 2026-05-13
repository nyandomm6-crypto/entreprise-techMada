<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/db-test', 'Home::dbTest');

// emlpoye
$routes->group('', ['filter' => 'employe'], function($routes) {
    $routes->get('employes/dashboard', 'employe\DashboardController::index');
});


// rh
$routes->group('', ['filter' => 'rh'], function($routes) {
    $routes->get('rh/dashboard', 'rh\DashboardController::index');
});

// admin
$routes->group('', ['filter' => 'admin'], function($routes) {
    $routes->get('admin/dashboard', 'rh\DashboardController::index');
});