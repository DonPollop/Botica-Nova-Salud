<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

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
    <title>Registrar Venta - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/registro_venta.css">
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
        <a href="alerts.php"><i class="fas fa-bell me-2"></i> Alertas</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <span class="navbar-brand">Registrar Venta</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Formulario de venta rápida -->
        <div class="container mt-4">
            <div class="quick-sale-form">
                <h2>Registrar Venta</h2>
                <form action="add_venta.php" method="POST"> <!-- Cambiado de 'add_sale.php' a 'add_venta.php' -->
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
                    <button type="submit" class="btn btn-primary">Registrar Venta</button>
                </form>
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