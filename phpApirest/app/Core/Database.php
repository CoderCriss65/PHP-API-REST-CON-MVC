<?php
namespace App\Core;

class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "123";
    private $database = "empresa";
    protected $conn;

    public function __construct() {
        $this->conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8");
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return ['error' => $this->conn->error];
        }
        
        if (!empty($params)) {
            $types = '';
            $values = [];
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            $stmt->bind_param($types, ...$values);
        }
        
        $stmt->execute();
        
        if ($stmt->error) {
            return ['error' => $stmt->error];
        }
        
        $result = $stmt->get_result();
        
        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $stmt->close();
            return $data;
        } else {
            $affected_rows = $stmt->affected_rows;
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return [
                'affected_rows' => $affected_rows,
                'insert_id' => $insert_id
            ];
        }
    }

    public function close() {
        $this->conn->close();
    }
}