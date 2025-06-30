<?php
// Registrar método y URI
error_log("[" . date('Y-m-d H:i:s') . "] " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);

// Para archivos estáticos
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|html|ico)$/', $_SERVER["REQUEST_URI"])) {
    $file = __DIR__ . '/public' . $_SERVER["REQUEST_URI"];
    if (file_exists($file) && is_file($file)) {
        return false;
    }
}

// Redirigir al index.php
include __DIR__ . '/public/index.php';