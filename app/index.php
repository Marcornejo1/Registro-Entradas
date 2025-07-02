<!DOCTYPE html>
<html>
<head>
  <title>Registro de Visitas</title>
  <!-- CDN QR -->
  <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
  <!-- Nuestro JS -->
  <script src="js/script.js" defer></script>
  <!-- Estilos -->
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/variables.css">
</head>

<body>
  <div class="container">
    <header class="header">
      <h1>Registro de Visitas</h1>
    </header>

    <form id="registroForm" class="form">
      <div class="form-group">
        <label for="nombre">Nombre Completo</label>
        <input type="text" id="nombre" name="nombre" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>

      <button type="submit" class="btn">Generar QR</button>
    </form>

    <div id="qrCode" class="qr-container" style="display: flex;">
      <h3>Tu código de acceso</h3>
      <!-- QR se generará aquí -->
    </div>
  </div>
</body>

</html>