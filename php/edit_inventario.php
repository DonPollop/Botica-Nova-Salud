<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

// Verificar si se pasó un ID de producto
if (!isset($_GET['id'])) {
    header('Location: inventario.php');
    exit;
}

$product_id = (int)$_GET['id'];

// Obtener los datos del producto
try {
    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = :id');
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: inventario.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error al obtener el producto: " . $e->getMessage());
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $min_stock = (int)$_POST['min_stock'];

    // Validar datos
    if (empty($name) || $precio <= 0 || $stock < 0 || $min_stock < 0) {
        $error = "Por favor, completa todos los campos correctamente.";
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE productos SET name = :name, precio = :precio, stock = :stock, min_stock = :min_stock WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'precio' => $precio,
                'stock' => $stock,
                'min_stock' => $min_stock,
                'id' => $product_id
            ]);
            header('Location: inventario.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = "Error al actualizar el producto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/edit_inventario.css">
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
                <span class="navbar-brand">Editar Producto</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Formulario de edición -->
        <div class="container mt-4">
            <div class="edit-form">
                <h2>Editar Producto</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form action="edit_inventario.php?id=<?php echo $product_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Precio (S/)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="precio" value="<?php echo htmlspecialchars($product['precio']); ?>" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock Actual</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="min_stock" class="form-label">Stock Mínimo</label>
                        <input type="number" class="form-control" id="min_stock" name="min_stock" value="<?php echo htmlspecialchars($product['min_stock']); ?>" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                    <a href="alerts.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>