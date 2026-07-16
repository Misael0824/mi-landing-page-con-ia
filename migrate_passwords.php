<?php
require_once __DIR__ . '/config.php';

if ($pdo === null) {
    echo "Error: no se pudo conectar a la base de datos.\n";
    exit(1);
}

$stmt = $pdo->query('SELECT id, email, password FROM usuarios');
$users = $stmt->fetchAll();

$updated = 0;
foreach ($users as $user) {
    $password = $user['password'];
    $info = password_get_info($password);

    if ($info['algo'] === 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
        $update->execute([$hash, $user['id']]);
        $updated++;
        echo "Usuario {$user['email']} actualizado.\n";
    }
}

echo "Migración completada: {$updated} contraseñas actualizadas.\n";
