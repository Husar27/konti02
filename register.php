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
    if ($user['login'] === $data['login']) {
        http_response_code(409);
        echo json_encode(['error' => 'Login już istnieje.']);
        exit;
    }
}
$users[] = [
    'login' => $data['login'],
    'password' => password_hash($data['password'], PASSWORD_DEFAULT)
];
file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
http_response_code(201);
echo json_encode(['message' => 'Użytkownik zarejestrowany.']);
