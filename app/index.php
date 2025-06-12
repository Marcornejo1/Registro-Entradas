<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registro de entrada</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <h1 class="container mt-5">Registro de entrada</h1>
    <form id="registroForm" class="mt-4">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="nombre" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" required>
      </div>
      <button type="submit" class="btn btn-primary">Generar QR</button>
    </form>
    <div id="qrContainer" class="mt-4 text-center d-none">
      <h3>Tu código QR para ingresar:</h3>
      <div id="qrcode"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
  <script src="app.js"></script>

</body>

</html>