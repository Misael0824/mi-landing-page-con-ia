<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($pdo === null) {
    $_SESSION['login_error'] = $dbError ?? 'No se pudo conectar a la base de datos.';
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nombre, email, password, rol, estado FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && (int) $user['estado'] === 1) {
    $passwordOk = false;
    $needsRehash = false;

    if (password_verify($password, $user['password'])) {
        $passwordOk = true;
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            $needsRehash = true;
        }
    } elseif ($user['password'] === $password) {
        $passwordOk = true;
        $needsRehash = true;
    }

    if ($passwordOk) {
        if ($needsRehash && $pdo !== null) {
            $updateStmt = $pdo->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
            $updateStmt->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
        }

        $_SESSION['usuario_id'] = (int) $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        header('Location: ' . (($user['rol'] ?? 'user') === 'admin' ? 'dashboard.php' : 'index.php'));
        exit;
    }
}

$_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
header('Location: login.php');
exit;
