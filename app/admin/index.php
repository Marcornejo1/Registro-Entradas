<?php
// Autenticación básica
if (
  !isset($_SERVER['PHP_AUTH_USER']) ||
  $_SERVER['PHP_AUTH_USER'] != 'admin' ||
  $_SERVER['PHP_AUTH_PW'] != 'admin1234'
) {
  header('WWW-Authenticate: Basic realm="Panel Admin"');
  header('HTTP/1.0 401 Unauthorized');
  exit('Acceso denegado');
}

require_once '../conexion.php';
$registros = $conn->query("SELECT * FROM registros ORDER BY fecha_registro DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Panel Admin</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
  <div class="admin-container">
    <h1>Registros de Visitas</h1>
    <table class="registros-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Estado</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($registros as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id']) ?></td>
            <td><?= htmlspecialchars($r['nombre']) ?></td>
            <td class="<?= $r['usado'] ? 'used' : 'pending' ?>">
              <?= $r['usado'] ? '✅ Usado' : '⏳ Pendiente' ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="loading-indicator" id="loadingIndicator">
        <div class="spinner"></div>
        <span>Actualizando...</span>
    </div>

  <script src="../js/admin-script.js"></script>
</body>

</html>