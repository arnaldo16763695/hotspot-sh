<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::adminLogin');
$routes->get('hotspot', 'Home::index');
$routes->post('hotspot/identify', 'Home::identify');
$routes->post('hotspot/register', 'Home::register');
$routes->get('hotspot/success', 'Home::success');
