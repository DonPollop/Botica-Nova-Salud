<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

// Obtener todas las ventas con información del producto
try {
    $stmt = $pdo->query('SELECT v.*, p.name AS product_name FROM venta v JOIN productos p ON v.product_id = p.id ORDER BY v.date DESC');
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener ventas: " . $e->getMessage());
}

// Obtener todos los productos para el formulario
try {
    $stmt = $pdo->query('SELECT id, name, precio, stock FROM productos ORDER BY name ASC');
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
    <title>Ventas - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/venta.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Nova Salud</div>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="inventario.php"><i class="fas fa-boxes me-2"></i> Inventario</a>
        <a href="venta.php"><i class="fas fa-shopping-cart me-2"></i> Ventas</a>
        <a href="registrar_venta.php"><i class="fas fa-cart-plus me-2"></i> Registrar Venta</a> <!-- Nueva opción para "Registrar Venta" -->
        <a href="alerts.php"><i class="fas fa-bell me-2"></i> Alertas</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <span class="navbar-brand">Ventas</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Venta registrada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al registrar la venta. Verifica los datos o el stock disponible.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabla de ventas -->
        <div class="container mt-4">
            <div class="d-flex justify-content-between mb-3">
                <h2>Registro de Ventas</h2>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSaleModal">
                    <i class="fas fa-plus me-2"></i> Registrar Venta
                </a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Ítems Vendidos</th>
                        <th>Total (S/)</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venta['id']); ?></td>
                            <td><?php echo htmlspecialchars($venta['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($venta['items_vendidos']); ?></td>
                            <td><?php echo number_format($venta['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($venta['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para registrar venta -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" aria-labelledby="addSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSaleModalLabel">Registrar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_venta.php" method="POST"> 
                        <div class="mb-3">
                            <label for="productId" class="form-label">Producto</label>
                            <select class="form-control" id="productId" name="product_id" required>
                                <option value="">Selecciona un producto</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['precio']; ?>" data-stock="<?php echo $product['stock']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?> (Stock: <?php echo $product['stock']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemsVendidos" class="form-label">Ítems Vendidos</label>
                            <input type="number" class="form-control" id="itemsVendidos" name="items_vendidos" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total (S/)</label>
                            <input type="text" class="form-control" id="total" name="total" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Calcular el total automáticamente
        const productSelect = document.getElementById('productId');
        const itemsVendidosInput = document.getElementById('itemsVendidos');
        const totalInput = document.getElementById('total');

        function calculateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const itemsVendidos = parseInt(itemsVendidosInput.value);
            if (price && itemsVendidos) {
                const total = price * itemsVendidos;
                totalInput.value = total.toFixed(2);
            } else {
                totalInput.value = '';
            }
        }

        productSelect.addEventListener('change', calculateTotal);
        itemsVendidosInput.addEventListener('input', calculateTotal);
    </script>
</body>
</html>