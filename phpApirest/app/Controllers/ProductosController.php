<?php
namespace App\Controllers;

use App\Models\ProductoModel;

class ProductosController {
    protected $model;

    public function __construct() {
        $this->model = new ProductoModel();
    }

    public function getAll() {
        $productos = $this->model->getAll();
        echo json_encode($productos);
    }

    public function getById($id) {
        $producto = $this->model->getById($id);
        
        if ($producto) {
            echo json_encode($producto);
        } else {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Producto no encontrado']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['precio']) || empty($data['stock'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        $result = $this->model->create($data['nombre'], $data['precio'], $data['stock']);
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } else {
            http_response_code(201);
            echo json_encode(['mensaje' => 'Producto agregado', 'id' => $result['insert_id']]);
        }
    }

    public function update($id) {
        // Forzar cabecera JSON
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['precio']) || empty($data['stock'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        // Verificar si el producto existe
        $productoExistente = $this->model->getById($id);
        if (!$productoExistente) {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Producto no encontrado']);
            return;
        }
        
        $result = $this->model->update(
            $id,
            $data['nombre'],
            $data['precio'],
            $data['stock']
        );
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } elseif ($result['affected_rows'] > 0) {
            // Obtener producto actualizado
            $productoActualizado = $this->model->getById($id);
            echo json_encode([
                'mensaje' => 'Producto actualizado correctamente',
                'producto' => $productoActualizado
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'No se realizaron cambios en el producto',
                'producto' => $productoExistente
            ]);
        }
    }
    public function delete($id) {
        $result = $this->model->delete($id);
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } elseif ($result['affected_rows'] === 0) {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Producto no encontrado']);
        } else {
            echo json_encode(['mensaje' => 'Producto eliminado correctamente']);
        }
    }
}