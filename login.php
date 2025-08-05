<?php
session_start();
require_once 'includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos
    $email = trim($_POST['email'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';

    // Validaciones
    if (empty($email) || empty($contraseña)) {
        $mensaje = 'Por favor, rellena todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El email no es válido.';
    } else {
        // Comprobar si el email existe
        $stmt = $pdo->prepare('SELECT id, nombre, contraseña FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            // Login exitoso: iniciar sesión y guardar datos de usuario
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $mensaje = '¡Bienvenido, ' . htmlspecialchars($usuario['nombre']) . '!';
            // Redirigir al usuario a otra página si es necesario
            header('Location: perfil.php');
        exit;
    } else {
            // Login fallido
            $mensaje = 'Email o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accede a tu nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="rectangle-3"></div>
        <div class="login-content">
            <div class="login-header">
                <h1 class="hero-title">Accede a tu nook</h1>
                <div class="login-buttons">
                    <button class="hero-btn">Iniciar Sesión</button>
                    <button class="hero-btn hero-btn-outline" onclick="window.location.href='register.php'">Registrarme</button>
                </div>
            </div>
            <?php if ($mensaje): ?>
                <p class="login-message"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>
            <div class="login-form-box">
                <form method="post" class="login-form">
                    <h2 class="login-form-title">Tu propio rincón en The Art Nook</h2>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="email@ejemplo.com" required>
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" placeholder="contraseña" required>
                    <button type="submit" class="hero-btn">Entrar</button>
</form>
                <div class="login-img"></div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html>
