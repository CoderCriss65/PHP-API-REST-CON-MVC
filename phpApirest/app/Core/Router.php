<?php
namespace App\Core;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public function get($pattern, $handler) {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post($pattern, $handler) {
        $this->addRoute('POST', $pattern, $handler);
    }

    public function put($pattern, $handler) {
        $this->addRoute('PUT', $pattern, $handler);
    }

    public function delete($pattern, $handler) {
        $this->addRoute('DELETE', $pattern, $handler);
    }

    private function addRoute($method, $pattern, $handler) {
        $this->routes[$method][$pattern] = $handler;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Manejar métodos PUT/DELETE desde formularios HTML
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        // Verificar rutas estáticas primero
        if (isset($this->routes[$method][$uri])) {
            $this->callHandler($this->routes[$method][$uri], []);
            return;
        }

        // Buscar en rutas dinámicas
        foreach ($this->routes[$method] as $pattern => $handler) {
            $pattern = preg_replace('/:(\w+)/', '(?P<$1>[^\/]+)', $pattern);
            $regex = "#^{$pattern}$#";
            
            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($handler, $params);
                return;
            }
        }

        // Si no se encuentra la ruta
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }

    private function callHandler($handler, $params) {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }
        
        if (is_string($handler)) {
            list($controllerClass, $methodName) = explode('@', $handler);
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                
                if (method_exists($controllerInstance, $methodName)) {
                    call_user_func_array([$controllerInstance, $methodName], $params);
                    return;
                }
            }
        }
        
        http_response_code(500);
        echo json_encode(['error' => 'Manejador de ruta inválido']);
    }
}