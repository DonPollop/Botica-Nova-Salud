<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

// Procesar la actualización del stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = (int)$_POST['product_id'];
    $new_stock = (int)$_POST['new_stock'];

    // Validar el nuevo stock
    if ($new_stock < 0) {
        header('Location: alerts.php?error=1');
        exit;
    }

    try {
        // Actualizar el stock en la base de datos
        $stmt = $pdo->prepare('UPDATE productos SET stock = :stock WHERE id = :id');
        $stmt->execute([
            'stock' => $new_stock,
            'id' => $product_id
        ]);

        header('Location: alerts.php?success=1');
        exit;
    } catch (PDOException $e) {
        header('Location: alerts.php?error=2');
        exit;
    }
}

// Obtener productos con stock bajo
try {
    $stmt = $pdo->query('SELECT id, name, stock, min_stock FROM productos WHERE stock <= min_stock ORDER BY stock ASC');
    $products_low_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener productos con stock bajo: " . $e->getMessage());
}

// Obtener conteo de alertas para la barra lateral
try {
    $stmt = $pdo->query('SELECT COUNT(*) FROM productos WHERE stock <= min_stock');
    $alert_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error al obtener conteo de alertas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/alerts.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Nova Salud</div>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="inventario.php"><i class="fas fa-boxes me-2"></i> Inventario</a>
        <a href="venta.php"><i class="fas fa-shopping-cart me-2"></i> Ventas</a>
        <a href="registrar_venta.php"><i class="fas fa-cart-plus me-2"></i> Registrar Venta</a>
        <a href="alerts.php">
            <i class="fas fa-bell me-2"></i> Alertas
            <?php if ($alert_count > 0): ?>
                <span class="badge"><?php echo $alert_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <span class="navbar-brand">Alertas</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Stock actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                if ($_GET['error'] == 1) {
                    echo "El stock no puede ser negativo.";
                } else {
                    echo "Error al actualizar el stock. Intenta de nuevo.";
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabla de alertas -->
        <div class="container mt-4">
            <h2>Productos con Stock Bajo</h2>
            <?php if (empty($products_low_stock)): ?>
                <div class="alert alert-success" role="alert">
                    ¡No hay productos con stock bajo en este momento!
                </div>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Nuevo Stock</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products_low_stock as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                <td><?php echo htmlspecialchars($product['min_stock']); ?></td>
                                <td>
                                    <form action="alerts.php" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="number" name="new_stock" class="form-control stock-input me-2" min="0" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                                        <button type="submit" name="update_stock" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save me-1"></i> Actualizar
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="edit_inventario.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Editar Producto
                                    </a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>