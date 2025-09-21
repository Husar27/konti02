<?php
// register.php
require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['login'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Login i hasło są wymagane.']);
    exit;
}

$login = $data['login'];
$password = $data['password'];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->prepare('SELECT id FROM users WHERE login = ?');
    $stmt->execute([$login]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Login już istnieje.']);
        exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (login, password) VALUES (?, ?)');
    $stmt->execute([$login, $hash]);
    http_response_code(201);
    echo json_encode(['message' => 'Użytkownik zarejestrowany.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd serwera.']);
}
