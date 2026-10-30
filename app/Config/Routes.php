<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// Autenticación
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');

$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);

// Administrador
$routes->group('admin', [
    'filter' => 'verificarRol:ADMINISTRADOR'
], static function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('usuarios', 'AdminController::listarUsuarios');
    $routes->post(
        'usuarios/actualizar-rol',
        'AdminController::actualizarRol'
    );
});

// Empleado y administrador
$routes->group('empleado', [
    'filter' => 'verificarRol:EMPLEADO,ADMINISTRADOR'
], static function ($routes) {
    $routes->get('panel', 'EmpleadoController::index');
    $routes->get('pedidos', 'PedidosController::listar');
});

// Cliente
$routes->group('cliente', [
    'filter' => 'verificarRol:CLIENTE'
], static function ($routes) {
    $routes->get('mi-cuenta', 'ClienteController::index');
});

// Compras: cada acción también valida el rol y la propiedad
// de la compra dentro del controlador.
$routes->group('compras', [
    'filter' => 'verificarRol:CLIENTE,EMPLEADO,ADMINISTRADOR'
], static function ($routes) {
    $routes->get('', 'Compras::index');
    $routes->get('nuevo', 'Compras::nuevo');
    $routes->post('guardar', 'Compras::guardar');
    $routes->get('editar/(:num)', 'Compras::editar/$1');
    $routes->post('actualizar/(:num)', 'Compras::actualizar/$1');
    $routes->post('eliminar/(:num)', 'Compras::eliminar/$1');
});

   $routes->get('/pago', 'pago::index');
   $routes->post('/pago', 'pago::form');
   $routes->get('pago/nuevo', 'Pago::nuevo');
$routes->post('pago/guardarWeb', 'Pago::guardarWeb');
$routes->get('sala', 'ButacaController::mostrarPantalla');
$routes->get('butaca','ButacaController::mostrarPantalla');
$routes->resource('butacacontroller', ['controller' => 'App\Controllers\ButacaController']);
$routes->get('compra/Combo', 'ButacaController::Combo');

$routes->get('combo', 'ComboController::index');








// 1. RUTAS DE AUTENTICACIÓN DE USUARIO
    // 1.1 Definí la ruta para mostrar el formulario de registro
    // 1.2 Definí la ruta para procesar el formulario de registro
    // 1.3 Definí la ruta para mostrar el formulario de inicio de sesión
    // 1.4 Definí la ruta para procesar el formulario de inicio de sesión
    // 1.5 Definí la ruta para cerrar sesión