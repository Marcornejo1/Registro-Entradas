<?php
// Configuración de encabezados
header('Content-Type: text/html; charset=UTF-8');


//Datos para la conexion a BD
$serverName = "host.docker.internal"; // Nombre del servicio en docker-compose (o la IP de tu SQL Server externo)
$database = "TestRegistros";
$user = "TestDB";
$password = "TestDB";

try {
  $conn = new PDO("sqlsrv:Server=host.docker.internal,1433;Database=TestRegistros;Encrypt=no", $user, $password);

} catch (PDOException $e) {
  die("<div class='alert alert-danger'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Procesamiento del código QR
$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

if (empty($codigo)) {
  die("<div class='alert alert-warning'>⚠️ No se proporcionó código QR</div>");
}

// Validar formato del código 
if (!preg_match('/^[a-f0-9]{8,20}$/i', $codigo)) {
  die("<div class='alert alert-danger'>❌ Código inválido. Debe contener solo letras (a-f) y números, entre 8 y 20 caracteres. Codigo: $codigo</div>");
}

// Verifica que no contenga caracteres especiales
if (preg_match('/[^a-f0-9]/i', $codigo)) {
    die("<div class='alert alert-danger'>❌ Código contiene caracteres no permitidos</div>");
}

// Consulta a la base de datos
try {
  $stmt = $conn->prepare("
        SELECT id, nombre, email, fecha_registro 
        FROM registros 
        WHERE codigo_qr = :codigo 
        AND usado = 0
        AND fecha_registro > DATEADD(HOUR, -24, GETDATE())
    ");

  $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
  $stmt->execute();
  $registro = $stmt->fetch();

  if ($registro) {
    // Marcar como usado
    $update = $conn->prepare("UPDATE registros SET usado = 1 WHERE id = :id");
    $update->bindParam(':id', $registro['id'], PDO::PARAM_INT);
    $update->execute();

    // Mostrar resultado exitoso
    mostrarResultado(true, $registro);
  } else {
    mostrarResultado(false);
  }
} catch (PDOException $e) {
  die("<div class='alert alert-danger'>❌ Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Función para mostrar resultados
function mostrarResultado($esValido, $datos = null)
{
  ?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/styles.css">
  </head>

  <body>
    <div class="container">
      <header class="header">
        <h1>Resultado de Verificación</h1>
      </header>

      <div class="resultado-container">
        <?php if ($esValido): ?>
          <div class="alert alert-success">
            <h2>✅ Acceso Autorizado</h2>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($datos['nombre']) ?></p>
            <p><strong>Fecha registro:</strong> <?= htmlspecialchars($datos['fecha_registro']) ?></p>
            <p class="timestamp"><?= date('Y-m-d H:i:s') ?></p>
          </div>
        <?php else: ?>
          <div class="alert alert-danger">
            <h2>❌ Acceso Denegado</h2>
            <p>El código QR no es válido o ya fue utilizado.</p>
            <p class="timestamp"><?= date('Y-m-d H:i:s') ?></p>
          </div>
        <?php endif; ?>

        <a href="/" class="btn">Volver al inicio</a>
      </div>
    </div>
  </body>

  </html>
  <?php
}
?>