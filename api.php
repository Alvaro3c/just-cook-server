<?php
// Cargar variables de entorno
$dotenv = __DIR__ . '/.env';
if (file_exists($dotenv)) {
    $lines = file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Obtener la API key
$apiKey = $_ENV['SPOONACULAR_KEY'] ?? '';

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'API key no configurada']);
    exit;
}

// Llamada a Spoonacular
$url = "https://api.spoonacular.com/recipes/random?apiKey={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// Devolver la respuesta al frontend
header('Content-Type: application/json');
echo $response;
