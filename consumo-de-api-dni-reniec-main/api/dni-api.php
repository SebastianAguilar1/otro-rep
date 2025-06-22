<?php
header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
if ($inputJSON) {
    $data = json_decode($inputJSON, true);
    if (isset($data['dato']) && strlen($data['dato']) === 8 && ctype_digit($data['dato'])) {
        $token = 'apis-token-16190.7PPp7WkhJZ3UC8cAb29EEWp3GbWVqlPz'; // Usa tu token real aquí
        $dni = $data['dato'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;
    } else {
        echo json_encode(['error' => true, 'message' => 'El DNI debe tener 8 dígitos numéricos']);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'No se recibió un JSON']);
}