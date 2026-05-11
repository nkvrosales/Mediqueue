<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Default redirect
$routes->get('/', 'Auth::index');

// Auth Routes
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Public Routes (no auth required)
$routes->get('kiosk', 'Kiosk::index');
$routes->post('kiosk/generate', 'Kiosk::generate');
$routes->get('kiosk/services/(:num)', 'Kiosk::getServices/$1');
$routes->get('display', 'Display::index');
$routes->get('display/data', 'Display::getData');

// Protected Routes (auth required)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/stats', 'Dashboard::getStats');
    $routes->get('dashboard/chart-data', 'Dashboard::getChartData');

    // Queue Management (staff + admin)
    $routes->get('queue', 'Queue::index');
    $routes->post('queue/call-next', 'Queue::callNext');
    $routes->post('queue/complete/(:num)', 'Queue::complete/$1');
    $routes->post('queue/skip/(:num)', 'Queue::skip/$1');
    $routes->post('queue/recall/(:num)', 'Queue::recall/$1');
    $routes->get('queue/status', 'Queue::getStatus');
    $routes->get('queue/status/(:num)', 'Queue::getStatus/$1');
});

// Admin Only Routes
$routes->group('', ['filter' => 'admin'], function ($routes) {
    // Departments
    $routes->get('departments', 'Departments::index');
    $routes->post('departments/store', 'Departments::store');
    $routes->get('departments/edit/(:num)', 'Departments::edit/$1');
    $routes->post('departments/update/(:num)', 'Departments::update/$1');
    $routes->post('departments/delete/(:num)', 'Departments::delete/$1');

    // Services
    $routes->get('services', 'Services::index');
    $routes->post('services/store', 'Services::store');
    $routes->get('services/edit/(:num)', 'Services::edit/$1');
    $routes->post('services/update/(:num)', 'Services::update/$1');
    $routes->post('services/delete/(:num)', 'Services::delete/$1');

    // Counters
    $routes->get('counters', 'Counters::index');
    $routes->post('counters/store', 'Counters::store');
    $routes->get('counters/edit/(:num)', 'Counters::edit/$1');
    $routes->post('counters/update/(:num)', 'Counters::update/$1');
    $routes->post('counters/delete/(:num)', 'Counters::delete/$1');

    // Users
    $routes->get('users', 'Users::index');
    $routes->post('users/store', 'Users::store');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->post('users/delete/(:num)', 'Users::delete/$1');

    // Reports
    $routes->get('reports', 'Reports::index');
    $routes->post('reports/generate', 'Reports::generate');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
