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

// 1. Пошук однієї книги для alert
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT p.*, c.categoryname FROM products p 
              INNER JOIN categories c ON p.categoryid = c.categoryid 
              WHERE p.productsid = $1";
    $result = pg_query_params($db_connect, $query, array($id));

    if ($result && $row = pg_fetch_assoc($result)) {
        echo json_encode([
            "title" => $row['title'],
            "description" => $row['description']
        ], JSON_UNESCAPED_UNICODE);
    }
    pg_close($db_connect);
    exit;
}

// 2. Фільтрація за категорією або вивід усіх книг
if (isset($_GET['category'])) {
    $catId = intval($_GET['category']);
    $query = "SELECT p.*, c.categoryname FROM products p 
              INNER JOIN categories c ON p.categoryid = c.categoryid 
              WHERE p.categoryid = $1 
              ORDER BY p.productsid DESC";
    $result = pg_query_params($db_connect, $query, array($catId));
} else {
    $query = "SELECT p.*, c.categoryname FROM products p 
              INNER JOIN categories c ON p.categoryid = c.categoryid 
              ORDER BY p.productsid DESC";
    $result = pg_query($db_connect, $query);
}

$products = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $products[] = [
            "id" => intval($row['productsid']),
            "title" => $row['title'],
            "author" => $row['author'],
            "publisher" => $row['publisher'],
            "category" => $row['categoryname'],
            "price" => floatval($row['price']),
            "image" => $row['image']
        ];
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
pg_close($db_connect);
