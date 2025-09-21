<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['login'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Login i hasło są wymagane.']);
    exit;
}
$file = 'users.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
foreach ($users as $user) {
    if ($user['login'] === $data['login'] && password_verify($data['password'], $user['password'])) {
        echo json_encode(['message' => 'Zalogowano pomyślnie.']);
        exit;
    }
}
http_response_code(401);
echo json_encode(['error' => 'Nieprawidłowy login lub hasło.']);
