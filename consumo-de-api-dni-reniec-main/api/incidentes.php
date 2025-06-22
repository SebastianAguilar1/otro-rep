<?php
header('Content-Type: application/json');
$archivo = __DIR__ . '/../data/incidentes.json';

// Leer incidentes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $incidentes = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
    $lista = isset($incidentes[$dni]) ? $incidentes[$dni] : [];
    echo json_encode(['incidentes' => $lista]);
    exit;
}

// Registrar incidente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['dni']) || !isset($data['habitacion']) || !isset($data['concepto'])) {
        echo json_encode(['error' => true, 'message' => 'Datos incompletos']);
        exit;
    }
    $dni = $data['dni'];
    $incidente = [
        'fecha' => date('Y-m-d H:i'),
        'habitacion' => $data['habitacion'],
        'acompanante' => $data['acompanante'] ?? '',
        'nombre_acompanante' => $data['nombre_acompanante'] ?? '',
        'concepto' => $data['concepto']
    ];
    $incidentes = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
    $incidentes[$dni][] = $incidente;
    file_put_contents($archivo, json_encode($incidentes, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['error' => true, 'message' => 'Petición no válida']);