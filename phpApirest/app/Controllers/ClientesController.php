<?php
namespace App\Controllers;

use App\Models\ClienteModel;

class ClientesController {
    protected $model;

    public function __construct() {
        $this->model = new ClienteModel();
    }

    public function getAll() {
        $clientes = $this->model->getAll();
        echo json_encode($clientes);
    }

    public function getById($id) {
        $cliente = $this->model->getById($id);
        
        if ($cliente) {
            echo json_encode($cliente);
        } else {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Cliente no encontrado']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['correo']) || empty($data['telefono'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        $result = $this->model->create($data['nombre'], $data['correo'], $data['telefono']);
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } else {
            http_response_code(201);
            echo json_encode(['mensaje' => 'Cliente agregado', 'id' => $result['insert_id']]);
        }
    }
    public function update($id) {
        // Forzar cabecera JSON
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['correo']) || empty($data['telefono'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        // Verificar si el cliente existe
        $clienteExistente = $this->model->getById($id);
        if (!$clienteExistente) {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Cliente no encontrado']);
            return;
        }
        
        $result = $this->model->update(
            $id,
            $data['nombre'],
            $data['correo'],
            $data['telefono']
        );
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } elseif ($result['affected_rows'] > 0) {
            // Obtener cliente actualizado
            $clienteActualizado = $this->model->getById($id);
            echo json_encode([
                'mensaje' => 'Cliente actualizado correctamente',
                'cliente' => $clienteActualizado
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'No se realizaron cambios en el cliente',
                'cliente' => $clienteExistente
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
            echo json_encode(['mensaje' => 'Cliente no encontrado']);
        } else {
            echo json_encode(['mensaje' => 'Cliente eliminado correctamente']);
        }
    }
}
