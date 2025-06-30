<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Cargar clases manualmente - SOLUCIÓN DEFINITIVA
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Database.php';

// Cargar modelos
require_once __DIR__ . '/../app/models/EmpleadoModel.php';
require_once __DIR__ . '/../app/models/ClienteModel.php';
require_once __DIR__ . '/../app/models/ProductoModel.php';

// Cargar controladores
require_once __DIR__ . '/../app/controllers/EmpleadosController.php';
require_once __DIR__ . '/../app/controllers/ClientesController.php';
require_once __DIR__ . '/../app/controllers/ProductosController.php';

use App\Core\Router;

$router = new Router();

// Rutas para Empleados
$router->get('/empleados', 'App\Controllers\EmpleadosController@getAll');
$router->get('/empleados/:id', 'App\Controllers\EmpleadosController@getById'); // Cambiado
$router->post('/empleados', 'App\Controllers\EmpleadosController@create');
$router->post('/empleados/masivo', 'App\Controllers\EmpleadosController@createMultiple');
$router->put('/empleados/:id', 'App\Controllers\EmpleadosController@update'); // Cambiado
$router->delete('/empleados/:id', 'App\Controllers\EmpleadosController@delete'); // Cambiado

// Rutas para Clientes
$router->get('/clientes', 'App\Controllers\ClientesController@getAll');
$router->get('/clientes/:id', 'App\Controllers\ClientesController@getById'); // Cambiado
$router->post('/clientes', 'App\Controllers\ClientesController@create');
$router->put('/clientes/:id', 'App\Controllers\ClientesController@update'); // Cambiado
$router->delete('/clientes/:id', 'App\Controllers\ClientesController@delete'); // Cambiado

// Rutas para Productos
$router->get('/productos', 'App\Controllers\ProductosController@getAll');
$router->get('/productos/:id', 'App\Controllers\ProductosController@getById'); // Cambiado
$router->post('/productos', 'App\Controllers\ProductosController@create');
$router->put('/productos/:id', 'App\Controllers\ProductosController@update'); // Cambiado
$router->delete('/productos/:id', 'App\Controllers\ProductosController@delete'); // Cambiado

// Ruta principal para servir la vista
$router->get('/', function() {
    header("Content-Type: text/html");
    readfile(__DIR__ . '/views/index.html');
});

// Manejar la solicitud
$router->dispatch();

// Convertir todas las respuestas a JSON
$output = ob_get_contents();
if (ob_get_length()) ob_end_clean();

if (!empty($output)) {
    // Verificar si ya es JSON
    if (json_decode($output) === null && json_last_error() === JSON_ERROR_NONE) {
        header('Content-Type: application/json');
        echo json_encode(['response' => $output]);
    } else {
        header('Content-Type: application/json');
        echo $output;
    }
}