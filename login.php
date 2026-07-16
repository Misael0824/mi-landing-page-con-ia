<?php
require_once __DIR__ . '/config.php';

if (isLoggedIn()) {
    header('Location: ' . (currentUserRole() === 'admin' ? 'dashboard.php' : 'index.php'));
    exit;
}

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión | Fries N' Fries</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="auth-shell">
    <div class="auth-card">
      <div class="auth-card__header">
        <h1>Iniciar sesión</h1>
        <p>Accede al dashboard o a la landing según tu rol.</p>
      </div>
      <?php if ($error): ?>
        <div class="message message--error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form action="validar.php" method="post" class="auth-form">
        <label class="field">
          <span>Correo</span>
          <input type="email" name="email" required placeholder="correo@dominio.com" />
        </label>
        <label class="field">
          <span>Contraseña</span>
          <input type="password" name="password" required placeholder="Tu contraseña" />
        </label>
        <button type="submit" class="btn btn-primary">Ingresar</button>
      </form>
      <div class="auth-links">
        <a href="index.php">Volver a la landing</a>
      </div>
    </div>
  </div>
</body>
</html>
