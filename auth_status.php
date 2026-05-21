<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['username'])) {
    echo json_encode([
        'logged' => true,
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'] ?? 'user'
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['logged' => false]);
}
