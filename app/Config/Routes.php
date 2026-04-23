<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::attemptLogin');
$routes->get('admin/login', 'Admin\AuthController::login');
$routes->get('admin/logout', 'Admin\AuthController::logout', ['filter' => 'adminauth']);
$routes->get('admin', 'Admin\DashboardController::index', ['filter' => 'adminauth']);
$routes->get('admin/customers', 'Admin\CustomersController::index', ['filter' => 'adminauth']);
$routes->get('admin/sessions', 'Admin\SessionsController::index', ['filter' => 'adminauth']);
$routes->get('admin/management', 'Admin\ManagementController::index', ['filter' => 'adminauth']);
$routes->get('hotspot', 'Home::index');
$routes->post('hotspot/identify', 'Home::identify');
$routes->post('hotspot/register', 'Home::register');
$routes->get('hotspot/success', 'Home::success');
