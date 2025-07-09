<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/projects', 'Projects::index');
$routes->get('/monitoring', 'Monitoring::index');
$routes->get('/settings', 'Settings::index');

// API Routes
$routes->group('api', function ($routes) {
    // Projects CRUD + stats
    $routes->get('projects/stats', 'Api\Projects::stats');
    $routes->resource('projects', ['controller' => 'Api\Projects', 'except' => ['new','edit']]);

    // Technologies CRUD + list by project
    $routes->get('technologies/project/(:segment)', 'Api\Technologies::getByProject/$1');
    $routes->resource('technologies', ['controller' => 'Api\Technologies', 'except' => ['new','edit']]);

    // CRUD de verificação de integridade (checkProject = show, checkAll = create?)
    // e endpoints extras para "all" e "history"
    $routes->post('healthcheck/all', 'Api\HealthCheck::checkAll');
    $routes->get('healthcheck/history/(:segment)', 'Api\HealthCheck::getHistory/$1');
    $routes->resource('healthcheck', ['controller' => 'Api\HealthCheck', 'except' => ['new','edit']]);
});
