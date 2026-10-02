<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::processRegister');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::processLogin');

$routes->get('/logout', 'Auth::logout');

$routes->post('salir', 'Auth::salir', ['filter' => 'auth']);
$routes->get('panel', 'Panel::index', ['filter' => 'auth']);

$routes->group('compras', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', 'Compras::index'); // R
    $routes->get('nuevo', 'Compras::nuevo'); // C (formulario)
    $routes->post('guardar', 'Compras::guardar'); // C (guarda)
    $routes->get('editar/(:num)', 'Compras::editar/$1'); // U (formulario)
    $routes->post('actualizar/(:num)', 'Compras::actualizar/$1'); // U (guarda)
    $routes->post('eliminar/(:num)', 'Compras::eliminar/$1'); // D
   });

// 1. RUTAS DE AUTENTICACIÓN DE USUARIO
    // 1.1 Definí la ruta para mostrar el formulario de registro
    // 1.2 Definí la ruta para procesar el formulario de registro
    // 1.3 Definí la ruta para mostrar el formulario de inicio de sesión
    // 1.4 Definí la ruta para procesar el formulario de inicio de sesión
    // 1.5 Definí la ruta para cerrar sesión