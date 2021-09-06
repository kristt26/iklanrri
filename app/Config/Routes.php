<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth::index', ['filter' => 'ceklogin']);
$routes->get('/auth', 'Auth::index', ['filter' => 'ceklogin']);
$routes->get('/admin/home', 'admin/Home::index', ['filter' => 'auth']);
$routes->get('/admin/layanan', 'admin/Layanan::index', ['filter' => 'auth']);
$routes->get('/admin/tarif', 'admin/Tarif::index', ['filter' => 'auth']);
$routes->get('/admin/users', 'admin/Users::index', ['filter' => 'auth']);
$routes->get('/admin/laporan/iklan', 'admin/Laporan::iklan', ['filter' => 'auth']);
$routes->get('/admin/laporan/pendapatan', 'admin/Laporan::pendapatan', ['filter' => 'auth']);
$routes->get('/admin/statusbayar', 'admin/Statusbayar::index', ['filter' => 'auth']);
$routes->get('/admin/iklantayang', 'admin/Iklantayang::index', ['filter' => 'auth']);
$routes->get('/home', 'Home::index', ['filter' => 'auth']);
$routes->get('/iklan', 'Iklan::index', ['filter' => 'auth']);
$routes->get('/profile', 'Profile::index', ['filter' => 'auth']);
$routes->get('/admin/jadwal', 'admin/Jadwal::index', ['filter' => 'auth']);
$routes->get('/siaran/jadwal', 'siaran/Jadwal::index', ['filter' => 'auth']);
$routes->get('/jadwal', 'Jadwal::index', ['filter' => 'auth']);
$routes->get('/siaran/home', 'siaran/Home::index', ['filter' => 'auth']);
$routes->get('/siaran/order', 'siaran/Order::index', ['filter' => 'auth']);
$routes->get('/siaran/iklantayang', 'admin/Iklantayang::index', ['filter' => 'auth']);

// $routes->get('/home/getHome', 'Home::getHome', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}