<?php
// login.php
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
    $stmt = $pdo->prepare('SELECT * FROM users WHERE login = ?');
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Nieprawidłowy login lub hasło.']);
        exit;
    }
    echo json_encode(['message' => 'Zalogowano pomyślnie.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd serwera.']);
}
