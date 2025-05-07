<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

// Obtener todos los productos
try {
    $stmt = $pdo->query('SELECT * FROM productos ORDER BY id ASC');
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/inventario.css">
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
        <a href="alerts.php"><i class="fas fa-bell me-2"></i> Alertas</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <span class="navbar-brand">Inventario</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Tabla de inventario -->
        <div class="container mt-4">
            <div class="d-flex justify-content-between mb-3">
                <h2>Gestión de Inventario</h2>
                <a href="s" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus me-2"></i> Agregar Producto
                </a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Stock Mínimo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td class="<?php echo $product['stock'] <= $product['min_stock'] ? 'low-stock' : ''; ?>">
                                <?php echo htmlspecialchars($product['stock']); ?>
                            </td>
                            <td>S/ <?php echo number_format($product['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['min_stock']); ?></td>
                            <td>
                                <a href="edit_inventario.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                                <a href="delete_inventario.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- Modal para agregar producto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_producto.php" method="POST">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="productDescription" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="productStock" name="stock" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Precio (S/)</label>
                        <input type="number" step="0.01" class="form-control" id="productPrice" name="precio" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productMinStock" class="form-label">Stock Mínimo</label>
                        <input type="number" class="form-control" id="productMinStock" name="min_stock" min="0" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>