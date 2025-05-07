<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login - Botica Nova Salud</title>
</head>
<body>
    <div class="login-container">
        <h2>Botica Nova Salud - Iniciar Sesión</h2>
        <form action="php/login.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Iniciar Sesión</button>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <p class="error">Usuario o contraseña incorrectos</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>