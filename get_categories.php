<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

$host = "localhost";
$port = "5432";
$dbname = "Libro_db";
$user = "postgres";
$password = "lex0512";

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$db_connect = pg_connect($connection_string);

if (!$db_connect) {
    echo json_encode(["error" => "Помилка підключення"]);
    exit;
}

// Запит, де назви колонок приведені до нижнього регістру
$query = "SELECT categoryid, categoryname FROM categories ORDER BY categoryid ASC";
$result = pg_query($db_connect, $query);

$categories = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $categories[] = [
            "id" => intval($row['categoryid']),
            "name" => $row['categoryname']
        ];
    }
}

echo json_encode($categories, JSON_UNESCAPED_UNICODE);
pg_close($db_connect);
