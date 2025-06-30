<?php
namespace App\Models;

use App\Core\Database;

class ProductoModel extends Database {
    public function getAll() {
        return $this->query("SELECT * FROM productos");
    }

    public function getById($id) {
        $result = $this->query("SELECT * FROM productos WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }

    public function create($nombre, $precio, $stock) {
        return $this->query("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)", 
                            [$nombre, $precio, $stock]);
    }

    public function update($id, $nombre, $precio, $stock) {
        return $this->query("UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?", 
                            [$nombre, $precio, $stock, $id]);
    }

    public function delete($id) {
        return $this->query("DELETE FROM productos WHERE id = ?", [$id]);
    }
}