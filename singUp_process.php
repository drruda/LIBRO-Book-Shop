<?php
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Форму не було відправлено методом POST."]);
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$terms = isset($_POST['terms']);

if ($username === '' || $email === '' || $password === '') {
    echo json_encode(["success" => false, "message" => "Будь-ласка, заповніть усі поля."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Некоректний формат email."]);
    exit;
}

if (!$terms) {
    echo json_encode(["success" => false, "message" => "Ви повинні погодитися з умовами."]);
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
    echo json_encode(["success" => false, "message" => "Не вдалося підключитися до бази даних."]);
    exit;
}

$create_sql = "CREATE TABLE IF NOT EXISTS libro_users (
    userid SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
pg_query($db, $create_sql);
pg_query($db, "ALTER TABLE libro_users ADD COLUMN IF NOT EXISTS role VARCHAR(20) NOT NULL DEFAULT 'user'");

$check_sql = "SELECT 1 FROM libro_users WHERE email = $1";
$res = pg_query_params($db, $check_sql, array($email));
if ($res && pg_num_rows($res) > 0) {
    echo json_encode(["success" => false, "message" => "Користувач з таким email вже існує."]);
    pg_close($db);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$insert_sql = "INSERT INTO libro_users (username, email, password_hash, role) VALUES ($1, $2, $3, $4) RETURNING userid";
$insert_res = pg_query_params($db, $insert_sql, array($username, $email, $password_hash, 'user'));

if ($insert_res && ($row = pg_fetch_assoc($insert_res))) {
    echo json_encode(["success" => true, "message" => "Реєстрація успішна!"]);
} else {
    $dbError = trim(pg_last_error($db));
    $message = "Не вдалося створити користувача.";
    if ($dbError) {
        $message .= " (" . $dbError . ")";
    }
    echo json_encode(["success" => false, "message" => $message]);
}

pg_close($db);
