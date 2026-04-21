<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('hotspot/identify', 'Home::identify');
$routes->post('hotspot/register', 'Home::register');
$routes->get('success', 'Home::success');
