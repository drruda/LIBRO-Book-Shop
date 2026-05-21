<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Форма має бути відправлена методом POST.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Будь ласка, заповніть усі поля.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Некоректний формат email.']);
    exit;
}

$host = "localhost";
$port = "5432";
$dbname = "Libro_db";
$dbuser = "postgres";
$dbpass = "lex0512";

$conn_str = "host=$host port=$port dbname=$dbname user=$dbuser password=$dbpass";
$db = pg_connect($conn_str);

if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Не вдалося підключитися до бази даних.']);
    exit;
}

$query = "SELECT username, email, password_hash, role FROM libro_users WHERE email = $1 LIMIT 1";
$result = pg_query_params($db, $query, array($email));

if (!$result || pg_num_rows($result) === 0) {
    pg_close($db);
    echo json_encode(['success' => false, 'message' => 'Неправильний email або пароль.']);
    exit;
}

$user = pg_fetch_assoc($result);
pg_close($db);

if (!password_verify($password, $user['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Неправильний email або пароль.']);
    exit;
}

$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'] ?? 'user';

echo json_encode(['success' => true, 'message' => 'Вхід успішний.']);
exit;
