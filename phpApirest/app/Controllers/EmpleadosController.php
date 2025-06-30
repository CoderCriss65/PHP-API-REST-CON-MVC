<?php
namespace App\Controllers;

use App\Models\EmpleadoModel;

class EmpleadosController {
    protected $model;

    public function __construct() {
        $this->model = new EmpleadoModel();
    }

    public function getAll() {
        $empleados = $this->model->getAll();
        echo json_encode($empleados);
    }

    public function getById($id) {
        $empleado = $this->model->getById($id);
        
        if ($empleado) {
            echo json_encode($empleado);
        } else {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Empleado no encontrado']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['puesto']) || empty($data['salario'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        $result = $this->model->create($data['nombre'], $data['puesto'], $data['salario']);
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } else {
            http_response_code(201);
            echo json_encode(['mensaje' => 'Empleado agregado', 'id' => $result['insert_id']]);
        }
    }

    public function createMultiple() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!is_array($data) || empty($data)) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Debe enviar un array de empleados válido.']);
            return;
        }
        
        $result = $this->model->createMultiple($data);
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } else {
            http_response_code(201);
            echo json_encode(['mensaje' => 'Empleados agregados correctamente', 'filasInsertadas' => $result['affected_rows']]);
        }
    }

    public function update($id) {
        // Forzar cabecera JSON
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['puesto']) || empty($data['salario'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }
        
        // Verificar si el empleado existe
        $empleadoExistente = $this->model->getById($id);
        if (!$empleadoExistente) {
            http_response_code(404);
            echo json_encode(['mensaje' => 'Empleado no encontrado']);
            return;
        }
        
        $result = $this->model->update(
            $id,
            $data['nombre'],
            $data['puesto'],
            $data['salario']
        );
        
        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        } elseif ($result['affected_rows'] > 0) {
            // Obtener empleado actualizado
            $empleadoActualizado = $this->model->getById($id);
            echo json_encode([
                'mensaje' => 'Empleado actualizado correctamente',
                'empleado' => $empleadoActualizado
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'No se realizaron cambios en el empleado',
                'empleado' => $empleadoExistente
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
            echo json_encode(['mensaje' => 'Empleado no encontrado']);
        } else {
            echo json_encode(['mensaje' => 'Empleado eliminado correctamente']);
        }
    }
}