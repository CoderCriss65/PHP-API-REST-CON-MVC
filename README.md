Documentación Profesional del Proyecto PHP API REST
Estructura del Proyecto
plaintext
phpApirest/
├── app/
│   ├── Controllers/
│   │   ├── ClientesController.php
│   │   ├── EmpleadosController.php
│   │   └── ProductosController.php
│   ├── Core/
│   │   ├── Database.php
│   │   └── Router.php
│   └── Models/
│       ├── ClienteModel.php
│       ├── EmpleadoModel.php
│       └── ProductoModel.php
├── public/
│   ├── assets/
│   ├── views/
│   │   ├── .htaccess
│   │   └── index.html
│   ├── .htaccess
│   └── index.php
├── vendor/
├── composer.json
├── router.php
└── README.md
Requisitos Técnicos
PHP 7.4+ (con extensión MySQLi)

MySQL/MariaDB

Servidor web (Apache/Nginx) o PHP Built-in Server

Composer (para autoloading)

Configuración Inicial
Base de Datos:

Crear base de datos empresa

Configurar credenciales en app/Core/Database.php:

php
private $host = "localhost";
private $user = "root";
private $password = "123";
private $database = "empresa";
Autoloading (Composer):

bash
composer install
Arquitectura y Componentes Clave
1. Enrutamiento (Router.php)
Implementa sistema RESTful con soporte para métodos HTTP: GET, POST, PUT, DELETE

Manejo de parámetros dinámicos en URLs (/empleados/:id)

Soporte para override de métodos mediante _method (PUT/DELETE desde formularios)

Mapeo controlador@método

php
$router->get('/empleados', 'App\Controllers\EmpleadosController@getAll');
$router->put('/empleados/:id', 'App\Controllers\EmpleadosController@update');
2. Capa de Datos (Database.php)
Conexión MySQLi con consultas preparadas

Sistema de tipos dinámico (i, d, s)

Retorno estructurado de resultados:

php
return [
  'affected_rows' => $stmt->affected_rows,
  'insert_id' => $stmt->insert_id
];
3. Controladores (MVC)
EmpleadosController:

Soporte para creación masiva (createMultiple)

Validación de campos obligatorios

Manejo de códigos HTTP (201, 400, 404, 500)

ClientesController/ProductosController:

CRUD completo con validaciones

Respuestas JSON estandarizadas

4. Modelos
EmpleadoModel:

Transacciones para inserción masiva

Conversión explícita de tipos (salario → float)

ClienteModel/ProductoModel:

Operaciones básicas CRUD

Inyección SQL prevenida con parámetros

5. Frontend (index.html)
SPA (Single Page Application) con:

Sistema de pestañas dinámicas

CRUD mediante Fetch API

Notificaciones de operaciones

Consola API para pruebas

Diseño responsive con CSS Grid/Flexbox

Interfaz de usuario moderna con:

Animaciones CSS

Variables CSS para temas

Cards interactivas

Endpoints API
Método	Ruta	Controlador	Función
GET	/empleados	EmpleadosController::getAll	Listar empleados
POST	/empleados	EmpleadosController::create	Crear empleado
POST	/empleados/masivo	EmpleadosController::createMultiple	Creación masiva
PUT	/empleados/:id	EmpleadosController::update	Actualizar empleado
DELETE	/empleados/:id	EmpleadosController::delete	Eliminar empleado
Patrones similares para clientes y productos
Ejecución del Proyecto
Usando PHP Built-in Server:

bash
php -S 0.0.0.0:3000 router.php
Acceder via: http://localhost:3000

Configuración Apache (ejemplo .htaccess):

apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/index.php [QSA,L]
Flujo de Solicitudes
Diagram
Code
sequenceDiagram
    participant Cliente
    participant Router
    participant Controlador
    participant Modelo
    participant BD

    Cliente->>Router: GET /empleados
    Router->>Controlador: EmpleadosController@getAll
    Controlador->>Modelo: getAll()
    Modelo->>BD: SELECT * FROM empleados
    BD-->>Modelo: ResultSet
    Modelo-->>Controlador: Datos
    Controlador-->>Cliente: JSON Response
Pruebas API
Consola Integrada:

Disponible en pestaña "Documentación API"

Permite ejecutar cualquier endpoint con cuerpo JSON

Ejemplo cURL:

bash
curl -X POST http://localhost:3000/empleados \
-H "Content-Type: application/json" \
-d '{"nombre":"Ana López","puesto":"Desarrolladora","salario":4500}'
Manejo de Errores
400 Bad Request: Campos obligatorios faltantes

404 Not Found: Recurso no encontrado

500 Internal Server Error: Errores de base de datos

CORS: Configuración completa en public/index.php

php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
Optimizaciones Implementadas
Prevención de N+1 en consultas

Uso de consultas preparadas

Validación de tipos en modelos

Manejo de transacciones para operaciones masivas

Caché de resultados (pendiente implementar)

Sistema de logging en router.php

Recomendaciones para Producción
Implementar autenticación JWT

Añadir sistema de rate-limiting

Configurar CORS específicos en lugar de *





Sistema de Gestión Empresarial - API REST con PHP
Tabla de Contenidos
Estructura del Proyecto

Requisitos Técnicos

Configuración Inicial

Arquitectura del Sistema

Endpoints API

Ejecución del Proyecto

Frontend SPA

Manejo de Errores

Optimizaciones

Pruebas API

Estructura del Proyecto
plaintext
phpApirest/
├── app/
│   ├── Controllers/
│   │   ├── ClientesController.php
│   │   ├── EmpleadosController.php
│   │   └── ProductosController.php
│   ├── Core/
│   │   ├── Database.php
│   │   └── Router.php
│   └── Models/
│       ├── ClienteModel.php
│       ├── EmpleadoModel.php
│       └── ProductoModel.php
├── public/
│   ├── assets/
│   ├── views/
│   │   ├── .htaccess
│   │   └── index.html
│   ├── .htaccess
│   └── index.php
├── vendor/
├── composer.json
├── router.php
└── README.md
Requisitos Técnicos
PHP 7.4+ (con extensión MySQLi)

MySQL/MariaDB

Servidor web (Apache/Nginx) o PHP Built-in Server

Composer (para autoloading)

Configuración Inicial
Base de Datos:

Crear base de datos empresa

Configurar credenciales en app/Core/Database.php:

php
private $host = "localhost";
private $user = "root";
private $password = "123";
private $database = "empresa";
Autoloading (Composer):

bash
composer install
Arquitectura del Sistema
Componentes Clave
Enrutamiento (Router.php)

Sistema RESTful con soporte para métodos HTTP: GET, POST, PUT, DELETE

Manejo de parámetros dinámicos en URLs (/empleados/:id)

Soporte para override de métodos mediante _method

Mapeo controlador@método

Capa de Datos (Database.php)

Conexión MySQLi con consultas preparadas

Sistema de tipos dinámico (i, d, s)

Retorno estructurado de resultados

Controladores (MVC)

Validación de campos obligatorios

Manejo de códigos HTTP (201, 400, 404, 500)

Respuestas JSON estandarizadas

Modelos

Operaciones CRUD con prevención de inyección SQL

Conversión explícita de tipos de datos

Transacciones para operaciones masivas

Flujo de Solicitudes
Diagram
Code
sequenceDiagram
    participant Cliente
    participant Router
    participant Controlador
    participant Modelo
    participant BD

    Cliente->>Router: GET /empleados
    Router->>Controlador: EmpleadosController@getAll
    Controlador->>Modelo: getAll()
    Modelo->>BD: SELECT * FROM empleados
    BD-->>Modelo: ResultSet
    Modelo-->>Controlador: Datos
    Controlador-->>Cliente: JSON Response
Endpoints API
Empleados
Método	Ruta	Función
GET	/empleados	Listar empleados
GET	/empleados/:id	Obtener empleado
POST	/empleados	Crear empleado
POST	/empleados/masivo	Creación masiva
PUT	/empleados/:id	Actualizar empleado
DELETE	/empleados/:id	Eliminar empleado
Clientes
Método	Ruta	Función
GET	/clientes	Listar clientes
GET	/clientes/:id	Obtener cliente
POST	/clientes	Crear cliente
PUT	/clientes/:id	Actualizar cliente
DELETE	/clientes/:id	Eliminar cliente
Productos
Método	Ruta	Función
GET	/productos	Listar productos
GET	/productos/:id	Obtener producto
POST	/productos	Crear producto
PUT	/productos/:id	Actualizar producto
DELETE	/productos/:id	Eliminar producto
Ejecución del Proyecto
Usando PHP Built-in Server:

bash
php -S 0.0.0.0:3000 router.php
Acceder via: http://localhost:3000

Configuración Apache (ejemplo .htaccess):

apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php [QSA,L]
</IfModule>
Frontend SPA
El proyecto incluye una interfaz web moderna con las siguientes características:

Single Page Application con navegación por pestañas

CRUD completo para empleados, clientes y productos

Diseño responsive con CSS Grid/Flexbox

Tema personalizable con variables CSS

Consola API integrada para pruebas

Notificaciones de operaciones

Animaciones CSS para mejor experiencia de usuario

html
<!-- Ejemplo de componente de empleados -->
<div id="empleados" class="tab-content">
    <h2>Gestión de Empleados</h2>
    <form id="empleadoForm">
        <input type="text" id="empleadoNombre" placeholder="Nombre" required>
        <input type="text" id="empleadoPuesto" placeholder="Puesto" required>
        <input type="number" id="empleadoSalario" placeholder="Salario" step="0.01" required>
        <button type="submit">Agregar Empleado</button>
    </form>
    <div id="empleadosList"></div>
</div>
Manejo de Errores
400 Bad Request: Campos obligatorios faltantes

404 Not Found: Recurso no encontrado

500 Internal Server Error: Errores de base de datos

CORS: Configuración completa en public/index.php

php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
Optimizaciones
Prevención de N+1 en consultas

Uso de consultas preparadas para seguridad

Validación de tipos en modelos

Manejo de transacciones para operaciones masivas

Sistema de logging en router.php

Caché de resultados (pendiente implementar)

Pruebas API
Consola Integrada:

Disponible en pestaña "Documentación API"

Permite ejecutar cualquier endpoint con cuerpo JSON

Ejemplo cURL:

bash
# Crear nuevo empleado
curl -X POST http://localhost:3000/empleados \
-H "Content-Type: application/json" \
-d '{"nombre":"Ana López","puesto":"Desarrolladora","salario":4500}'

# Listar empleados
curl http://localhost:3000/empleados

# Actualizar empleado
curl -X PUT http://localhost:3000/empleados/1 \
-H "Content-Type: application/json" \
-d '{"nombre":"Ana García","puesto":"Senior Developer","salario":5500}'
Recomendaciones para Producción
Implementar autenticación JWT

Añadir sistema de rate-limiting

Configurar CORS específicos en lugar de *

Implementar migraciones de base de datos

Añadir pruebas unitarias con PHPUnit

Utilizar variables de entorno para credenciales

Este proyecto proporciona una base robusta para APIs RESTful en PHP, siguiendo mejores prácticas de seguridad, rendimiento y arquitectura MVC.





Implementar migraciones de base de datos

Añadir pruebas unitarias con PHPUnit

Configurar variables de entorno

Este proyecto proporciona una base robusta para APIs RESTful en PHP, siguiendo mejores prácticas de seguridad, rendimiento y arquitectura MVC.
