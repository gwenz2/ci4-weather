
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');

// Authentication routes
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::authenticate');
$routes->get('/logout', 'LoginController::logout');
$routes->get('/signup', 'LoginController::signup');
$routes->post('/signup/register', 'LoginController::register');

// Dashboard routes
$routes->get('/admin/dashboard', 'AdminController::dashboard');
$routes->get('/admin/cities', 'AdminController::cities');
$routes->get('/user/dashboard', 'UserController::dashboard');
$routes->get('/user/cities', 'UserController::cities');
$routes->get('/user/favorites/add/(:num)', 'UserController::addFavorite/$1');
$routes->get('/user/favorites/remove/(:num)', 'UserController::removeFavorite/$1');

// Admin CRUD routes
$routes->get('/admin/cities/create', 'AdminController::createCity');
$routes->post('/admin/cities/store', 'AdminController::storeCity');
$routes->get('/admin/cities/edit/(:num)', 'AdminController::editCity/$1');
$routes->post('/admin/cities/update/(:num)', 'AdminController::updateCity/$1');
$routes->get('/admin/cities/delete/(:num)', 'AdminController::deleteCity/$1');
$routes->get('/admin/users', 'AdminController::users');
$routes->get('/admin/users/edit/(:num)', 'AdminController::editUser/$1');
$routes->post('/admin/users/update/(:num)', 'AdminController::updateUser/$1');
$routes->get('/admin/users/delete/(:num)', 'AdminController::deleteUser/$1');
