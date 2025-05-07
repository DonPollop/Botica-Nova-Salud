<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM productos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        header('Location: inventario.php?success=2');
        exit;
    } catch (PDOException $e) {
        die("Error al eliminar producto: " . $e->getMessage());
    }
} else {
    header('Location: inventario.php?error=2');
    exit;
}
?>