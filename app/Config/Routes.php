<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->post('home/upload_csv', 'Home::uploadCSV');
$routes->get('/charts', 'Home::charts');




