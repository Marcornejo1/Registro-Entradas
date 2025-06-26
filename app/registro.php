<?php
header('Content-Type: application/json');

$serverName = "host.docker.internal"; // Nombre del servicio en docker-compose (o la IP de tu SQL Server externo)
$database = "TestRegistros";
$user = "TestDB";
$password = "TestDB";

try {
  $conn = new PDO("sqlsrv:Server=host.docker.internal,1433;Database=TestRegistros;Encrypt=no", $user, $password);

  // Verificar límite de 300 registros
  $stmt = $conn->query("SELECT COUNT(*) AS total FROM registros WHERE usado = 0");
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result['total'] >= 300) {
    die(json_encode(["success" => false, "message" => "Límite de 300 registros alcanzado"]));
  }

  // Generar código QR único
  $codigo_qr = bin2hex(random_bytes(10)); // 10 bytes = 20 caracteres hex
  $url_verificacion = "http://172.16.90.35:8080/verificar.php?codigo=" . $codigo_qr;

  // Insertar registro
  $stmt = $conn->prepare("INSERT INTO registros (nombre, email, codigo_qr) VALUES (?, ?, ?)");
  $stmt->execute([$_POST['nombre'], $_POST['email'], $codigo_qr]);

  echo json_encode(["success" => true, "codigo_qr" => $url_verificacion]);

} catch (PDOException $e) {
  echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>