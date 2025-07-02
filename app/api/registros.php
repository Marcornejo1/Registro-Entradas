<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../conexion.php';

try {
    $stmt = $conn->query("
        SELECT 
            id,
            nombre,
            email,
            codigo_qr,
            CONVERT(VARCHAR, fecha_registro, 120) as fecha_registro,
            CAST(usado AS INT) as usado  /* Conversión explícita a entero */
        FROM registros
        ORDER BY fecha_registro DESC
    ");
    
    echo json_encode([
        'success' => true,
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'data' => []
    ]);
}
?>