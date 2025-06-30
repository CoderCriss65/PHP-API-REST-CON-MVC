<?php
namespace App\Models;

use App\Core\Database;

class ClienteModel extends Database {
    public function getAll() {
        return $this->query("SELECT * FROM clientes");
    }

    public function getById($id) {
        $result = $this->query("SELECT * FROM clientes WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }

    public function create($nombre, $correo, $telefono) {
        return $this->query("INSERT INTO clientes (nombre, correo, telefono) VALUES (?, ?, ?)", 
                            [$nombre, $correo, $telefono]);
    }

    public function update($id, $nombre, $correo, $telefono) {
        // Convertir ID a entero explícitamente
        $id = (int)$id;
        
        return $this->query(
            "UPDATE clientes SET nombre = ?, correo = ?, telefono = ? WHERE id = ?", 
            [$nombre, $correo, $telefono, $id]
        );
    }

    public function delete($id) {
        return $this->query("DELETE FROM clientes WHERE id = ?", [$id]);
    }
}