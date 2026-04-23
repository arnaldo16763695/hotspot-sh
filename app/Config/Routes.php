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
$routes->get('admin/customers/(:num)', 'Admin\CustomersController::show/$1', ['filter' => 'adminauth']);
$routes->get('admin/sessions', 'Admin\SessionsController::index', ['filter' => 'adminauth']);
$routes->get('admin/users', 'Admin\UsersController::index', ['filter' => 'adminauth']);
$routes->get('admin/users/create', 'Admin\UsersController::create', ['filter' => 'adminauth']);
$routes->get('admin/users/edit/(:num)', 'Admin\UsersController::edit/$1', ['filter' => 'adminauth']);
$routes->post('admin/users/save', 'Admin\UsersController::save', ['filter' => 'adminauth']);
$routes->post('admin/users/toggle/(:num)', 'Admin\UsersController::toggle/$1', ['filter' => 'adminauth']);
$routes->get('admin/roles', 'Admin\RolesController::index', ['filter' => 'adminauth']);
$routes->get('admin/branches', 'Admin\BranchesController::index', ['filter' => 'adminauth']);
$routes->get('admin/branches/create', 'Admin\BranchesController::create', ['filter' => 'adminauth']);
$routes->get('admin/branches/edit/(:num)', 'Admin\BranchesController::edit/$1', ['filter' => 'adminauth']);
$routes->post('admin/branches/save', 'Admin\BranchesController::save', ['filter' => 'adminauth']);
$routes->post('admin/branches/toggle/(:num)', 'Admin\BranchesController::toggle/$1', ['filter' => 'adminauth']);
$routes->get('admin/routers', 'Admin\RoutersController::index', ['filter' => 'adminauth']);
$routes->get('admin/routers/create', 'Admin\RoutersController::create', ['filter' => 'adminauth']);
$routes->get('admin/routers/edit/(:num)', 'Admin\RoutersController::edit/$1', ['filter' => 'adminauth']);
$routes->post('admin/routers/save', 'Admin\RoutersController::save', ['filter' => 'adminauth']);
$routes->post('admin/routers/toggle/(:num)', 'Admin\RoutersController::toggle/$1', ['filter' => 'adminauth']);
$routes->get('admin/management', 'Admin\ManagementController::index', ['filter' => 'adminauth']);
$routes->get('hotspot', 'Home::index');
$routes->post('hotspot/identify', 'Home::identify');
$routes->post('hotspot/register', 'Home::register');
$routes->get('hotspot/success', 'Home::success');
