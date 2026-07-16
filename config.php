<?php
session_start();

$host = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'fries_db';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    $pdo = null;
    $dbError = 'No se pudo conectar a la base de datos. Revisa las variables de entorno en Railway.';
}

function requireAuth(string $requiredRole = null): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }

    if ($requiredRole && ($_SESSION['rol'] ?? 'user') !== $requiredRole) {
        header('Location: index.php');
        exit;
    }
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['usuario_id']);
}

function currentUserName(): string
{
    return $_SESSION['nombre'] ?? 'Usuario';
}

function currentUserRole(): string
{
    return $_SESSION['rol'] ?? 'user';
}
