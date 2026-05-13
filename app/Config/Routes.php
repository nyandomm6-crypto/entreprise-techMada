<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/db-test', 'Home::dbTest');
$routes->get('/login', 'front\EmployesController::loginView');
$routes->post('/login', 'front\EmployesController::loginPost');
$routes->get('/logout', 'front\EmployesController::logout');

// emlpoye
$routes->group('', ['filter' => 'employe'], function($routes) {
    $routes->get('/employes/dashboard', 'employe\DashboardController::index');
});


// rh
$routes->group('', ['filter' => 'rh'], function($routes) {
    $routes->get('/rh/dashboard', 'rh\DashboardController::index');
});

// admin
$routes->group('', ['filter' => 'admin'], function($routes) {
    $routes->get('/admin/dashboard', 'admin\DashboardController::index');
});