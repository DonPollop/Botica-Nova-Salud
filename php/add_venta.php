<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $items_vendidos = (int)$_POST['items_vendidos'];
    $total = (float)$_POST['total'];

    // Validar datos
    if ($product_id <= 0 || $items_vendidos <= 0 || $total <= 0) {
        header('Location: venta.php?error=1');
        exit;
    }

    // Iniciar una transacción para asegurar la consistencia
    try {
        $pdo->beginTransaction();

        // Verificar el stock disponible
        $stmt = $pdo->prepare('SELECT stock, precio FROM productos WHERE id = :id');
        $stmt->execute(['id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product || $product['stock'] < $items_vendidos) {
            $pdo->rollBack();
            header('Location: venta.php?error=1');
            exit;
        }

        // Calcular el total (para validar)
        $expected_total = $product['precio'] * $items_vendidos;
        if (abs($expected_total - $total) > 0.01) {
            $pdo->rollBack();
            header('Location: venta.php?error=1');
            exit;
        }

        // Registrar la venta
        $stmt = $pdo->prepare('INSERT INTO venta (product_id, items_vendidos, total) VALUES (:product_id, :items_vendidos, :total)');
        $stmt->execute([
            'product_id' => $product_id,
            'items_vendidos' => $items_vendidos,
            'total' => $total
        ]);

        // Actualizar el stock
        $new_stock = $product['stock'] - $items_vendidos;
        $stmt = $pdo->prepare('UPDATE productos SET stock = :stock WHERE id = :id');
        $stmt->execute([
            'stock' => $new_stock,
            'id' => $product_id
        ]);

        $pdo->commit();
        header('Location: venta.php?success=1');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error al registrar la venta: " . $e->getMessage());
    }
}
?>