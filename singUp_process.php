<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Збираємо дані
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $terms = isset($_POST['terms']) ? "Прийнято" : "Не прийнято";

    // Записуємо все в масив
    $registration_data = [
        "Тип форми" => "Реєстрація нового користувача LIBRO",
        "Прізвище та ім'я" => $username,
        "Електронна пошта" => $email,
        "Пароль (довжина)" => strlen($password) . " символів",
        "Згода з умовами" => $terms
    ];

    // Виводимо масив на сторінку
    echo "<h1>Результат обробки форми реєстрації</h1>";
    echo "<p>Акаунт успішно підготовлено до створення!</p>";

    echo "<h3>Вміст створеного PHP-масиву:</h3>";
    echo "<ul>";
    foreach ($registration_data as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
} 
else {
    echo "Помилка: форму не було відправлено.";
}
