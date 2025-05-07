<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../inicio.php');
    exit;
}

require 'conexion_db.php';

// Obtener conteo de productos con stock bajo
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
    <title>Dashboard - Botica Nova Salud</title>
    <link rel="stylesheet" href="css/darshboard.css">
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
                <span class="navbar-brand">Panel de Control</span>
                <div class="ms-auto user-info">
                    <i class="fas fa-user me-2"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
            </div>
        </nav>

        <!-- Contenido del Dashboard -->
        <div class="container mt-4">
            <div class="welcome-message mb-4">
                <h2>¡Bienvenido a Botica Nova Salud!</h2>
                <p>Administra tu inventario, ventas y alertas de manera eficiente.</p>
            </div>

            <div class="row">
                <!-- Tarjeta de Inventario -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Inventario
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Productos en Stock</h5>
                            <p class="card-text">Consulta y actualiza el inventario en tiempo real.</p>
                            <a href="inventario.php" class="btn btn-primary">Ver Inventario</a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Ventas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Ventas
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Registro de Ventas</h5>
                            <p class="card-text">Registra y revisa las ventas realizadas.</p>
                            <a href="registrar_venta.php" class="btn btn-primary">Registrar Venta</a> <!-- Cambiado a 'registrar_venta.php' -->
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Alertas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header alerts-header">
                            Alertas
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Productos Bajos en Stock</h5>
                            <p class="card-text">
                                Actualmente hay <?php echo $alert_count; ?> producto(s) con stock bajo.
                            </p>
                            <a href="alerts.php" class="btn btn-primary">Ver Alertas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>