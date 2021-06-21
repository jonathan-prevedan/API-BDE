<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
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
$routes->get('/', 'Home::index');

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



$routes->get('/user/infos','User::index');

$routes->post('user/register','User::register');
$routes->post('user/login','User::login');

$routes->get('user/edit/(:num)','User::details');

$routes->put('user/update/(:num)','User::update/$1');

$routes->delete('user/delete/(:num)','User::delete');


$routes->post('events/create', 'Events::create');
$routes->get('events/edit/(:num)', 'Events::details');
$routes->put('events/update/(:num)','Events::update/$1');

$routes->delete('events/delete(:num)','Events::delete');


// $routes->resource('user');

// $routes->group("api", function ($routes) {



    
//     // $routes->resource("user");
//     // $routes->get("index","User::index");
//     // $routes->post("register", "User::register");
//     // $routes->post("login", "User::login");
//     // $routes->get("profile", "User::details");
//     // $routes->put("update/(.*)", "User::update");
//     // $routes->delete("delete/(.*)", "User::delete");
// });
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
