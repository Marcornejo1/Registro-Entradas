<?php
$serverName = "host.docker.internal"; // Nombre del servicio en docker-compose (o la IP de tu SQL Server externo)
$database = "TestRegistros";
$user = "TestDB";
$password = "TestDB";

try {
    $conn = new PDO(
        "sqlsrv:Server=host.docker.internal,1433;Database=TestRegistros;Encrypt=no",
        "TestDB",
        "TestDB"
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ ¡Conexión exitosa a SQL Server!";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>