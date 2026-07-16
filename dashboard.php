<?php
require_once __DIR__ . '/config.php';
requireAuth('admin');

$feedback = $_SESSION['feedback'] ?? null;
$feedbackType = $_SESSION['feedback_type'] ?? 'success';
unset($_SESSION['feedback'], $_SESSION['feedback_type']);

if ($pdo === null) {
    $users = [];
    $sales = [];
    $dailySummary = ['total' => 0, 'cantidad' => 0];
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_user') {
        $userId = !empty($_POST['user_id']) ? (int) $_POST['user_id'] : null;
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = ($_POST['rol'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $password = trim($_POST['password'] ?? '');

        if ($nombre === '' || $email === '') {
            $_SESSION['feedback'] = 'Completa nombre y correo.';
            $_SESSION['feedback_type'] = 'error';
        } else {
            $checkStmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
            $checkStmt->execute([$email, $userId ?? 0]);
            if ($checkStmt->fetch()) {
                $_SESSION['feedback'] = 'Ese correo ya existe.';
                $_SESSION['feedback_type'] = 'error';
            } else {
                if ($userId) {
                    if ($password !== '') {
                        $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, email = ?, rol = ?, password = ? WHERE id = ?');
                        $stmt->execute([$nombre, $email, $rol, password_hash($password, PASSWORD_DEFAULT), $userId]);
                    } else {
                        $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?');
                        $stmt->execute([$nombre, $email, $rol, $userId]);
                    }
                    $_SESSION['feedback'] = 'Usuario actualizado correctamente.';
                } else {
                    if ($password === '') {
                        $_SESSION['feedback'] = 'La contraseña es obligatoria para crear un usuario.';
                        $_SESSION['feedback_type'] = 'error';
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (?, ?, ?, ?, 1)');
                        $stmt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $rol]);
                        $_SESSION['feedback'] = 'Usuario creado correctamente.';
                    }
                }
            }
        }

        header('Location: dashboard.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_sale') {
        $cliente = trim($_POST['cliente'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $monto = (float) ($_POST['monto'] ?? 0);
        $fecha = trim($_POST['fecha'] ?? date('Y-m-d'));

        if ($descripcion === '' || $monto <= 0) {
            $_SESSION['feedback'] = 'Completa descripción y monto.';
            $_SESSION['feedback_type'] = 'error';
        } else {
            $stmt = $pdo->prepare('INSERT INTO ventas (cliente, descripcion, monto, fecha) VALUES (?, ?, ?, ?)');
            $stmt->execute([$cliente, $descripcion, $monto, $fecha]);
            $_SESSION['feedback'] = 'Venta registrada correctamente.';
        }

        header('Location: dashboard.php');
        exit;
    }

    $editingId = !empty($_GET['edit']) ? (int) $_GET['edit'] : 0;
    $editingUser = null;
    if ($editingId > 0) {
        $stmt = $pdo->prepare('SELECT id, nombre, email, rol FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->execute([$editingId]);
        $editingUser = $stmt->fetch();
    }

    $usersStmt = $pdo->query('SELECT id, nombre, email, rol, estado FROM usuarios ORDER BY id DESC');
    $users = $usersStmt->fetchAll();

    $today = date('Y-m-d');
    $summaryStmt = $pdo->prepare('SELECT SUM(monto) AS total, COUNT(*) AS cantidad FROM ventas WHERE fecha = ?');
    $summaryStmt->execute([$today]);
    $dailySummary = $summaryStmt->fetch();

    $salesStmt = $pdo->prepare('SELECT cliente, descripcion, monto, fecha FROM ventas WHERE fecha = ? ORDER BY id DESC');
    $salesStmt->execute([$today]);
    $sales = $salesStmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Fries N' Fries</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="dashboard-shell">
    <header class="dashboard-header">
      <div>
        <p class="eyebrow eyebrow-dark">Panel de administración</p>
        <h1>Gestión de usuarios y ventas</h1>
      </div>
      <div class="dashboard-actions">
        <a href="index.php" class="btn btn-secondary">Ver landing</a>
        <a href="logout.php" class="btn btn-primary">Cerrar sesión</a>
      </div>
    </header>

    <?php if ($feedback): ?>
      <div class="message message--<?= $feedbackType === 'error' ? 'error' : 'success' ?>"><?= htmlspecialchars($feedback) ?></div>
    <?php endif; ?>

    <section class="dashboard-grid">
      <article class="panel">
        <h2><?= $editingUser ? 'Actualizar usuario' : 'Agregar usuario' ?></h2>
        <form action="dashboard.php" method="post" class="stack-form">
          <input type="hidden" name="action" value="save_user" />
          <?php if ($editingUser): ?>
            <input type="hidden" name="user_id" value="<?= (int) $editingUser['id'] ?>" />
          <?php endif; ?>
          <label class="field">
            <span>Nombre</span>
            <input type="text" name="nombre" value="<?= htmlspecialchars($editingUser['nombre'] ?? '') ?>" required />
          </label>
          <label class="field">
            <span>Correo</span>
            <input type="email" name="email" value="<?= htmlspecialchars($editingUser['email'] ?? '') ?>" required />
          </label>
          <label class="field">
            <span>Contraseña</span>
            <input type="password" name="password" placeholder="<?= $editingUser ? 'Deja vacío para mantenerla' : 'Mínimo 6 caracteres' ?>" />
          </label>
          <label class="field">
            <span>Rol</span>
            <select name="rol">
              <option value="user" <?= (($editingUser['rol'] ?? 'user') === 'user' ? 'selected' : '') ?>>User</option>
              <option value="admin" <?= (($editingUser['rol'] ?? 'user') === 'admin' ? 'selected' : '') ?>>Admin</option>
            </select>
          </label>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </article>

      <article class="panel">
        <h2>Ventas de hoy</h2>
        <div class="stats-card">
          <strong>$<?= number_format((float) ($dailySummary['total'] ?? 0), 2) ?></strong>
          <span><?= (int) ($dailySummary['cantidad'] ?? 0) ?> ventas</span>
        </div>
        <form action="dashboard.php" method="post" class="stack-form">
          <input type="hidden" name="action" value="save_sale" />
          <label class="field">
            <span>Cliente</span>
            <input type="text" name="cliente" placeholder="Nombre del cliente" />
          </label>
          <label class="field">
            <span>Descripción</span>
            <input type="text" name="descripcion" required placeholder="Ej. Combo familiar" />
          </label>
          <label class="field">
            <span>Monto</span>
            <input type="number" name="monto" step="0.01" min="0" required />
          </label>
          <label class="field">
            <span>Fecha</span>
            <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" />
          </label>
          <button type="submit" class="btn btn-primary">Registrar venta</button>
        </form>
      </article>
    </section>

    <section class="panel table-panel">
      <h2>Usuarios</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['nombre']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['rol']) ?></td>
              <td><?= (int) $user['estado'] === 1 ? 'Activo' : 'Inactivo' ?></td>
              <td><a href="dashboard.php?edit=<?= (int) $user['id'] ?>" class="link-action">Editar</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section class="panel table-panel">
      <h2>Ventas de hoy</h2>
      <table>
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sales as $sale): ?>
            <tr>
              <td><?= htmlspecialchars($sale['cliente'] ?? '-') ?></td>
              <td><?= htmlspecialchars($sale['descripcion']) ?></td>
              <td>$<?= number_format((float) $sale['monto'], 2) ?></td>
              <td><?= htmlspecialchars($sale['fecha']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>
