<?php
namespace App\Models;

use App\Core\Database;

class EmpleadoModel extends Database {
    public function getAll() {
        return $this->query("SELECT * FROM empleados");
    }

    public function getById($id) {
        $result = $this->query("SELECT * FROM empleados WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }

    public function create($nombre, $puesto, $salario) {
        return $this->query("INSERT INTO empleados (nombre, puesto, salario) VALUES (?, ?, ?)", 
                            [$nombre, $puesto, $salario]);
    }

    public function createMultiple($empleados) {
        $values = [];
        $params = [];
        
        foreach ($empleados as $empleado) {
            $values[] = "(?, ?, ?)";
            $params[] = $empleado['nombre'];
            $params[] = $empleado['puesto'];
            $params[] = $empleado['salario'];
        }
        
        $sql = "INSERT INTO empleados (nombre, puesto, salario) VALUES " . implode(',', $values);
        return $this->query($sql, $params);
    }

    public function update($id, $nombre, $puesto, $salario) {
        // Convertir ID a entero explícitamente
        $id = (int)$id;
        $salario = (float)$salario;  // Convertir salario a float
        
        return $this->query(
            "UPDATE empleados SET nombre = ?, puesto = ?, salario = ? WHERE id = ?", 
            [$nombre, $puesto, $salario, $id]
        );
    }

    public function delete($id) {
        return $this->query("DELETE FROM empleados WHERE id = ?", [$id]);
    }
}