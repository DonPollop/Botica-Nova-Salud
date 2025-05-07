<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $stock = (int)$_POST['stock'];
    $precio = (float)$_POST['precio'];
    $min_stock = (int)$_POST['min_stock'];

    // Validar datos
    if (empty($name) || $stock < 0 || $precio <= 0 || $min_stock < 0) {
        header('Location: inventario.php?error=1');
        exit;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO productos (name, description, stock, precio, min_stock) VALUES (:name, :description, :stock, :precio, :min_stock)');
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'stock' => $stock,
            'precio' => $precio,
            'min_stock' => $min_stock
        ]);
        header('Location: inventario.php?success=1');
        exit;
    } catch (PDOException $e) {
        die("Error al agregar producto: " . $e->getMessage());
    }
}
?>