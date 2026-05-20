<?php
// Перевіряємо, чи дані прийшли саме методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Збираємо дані з полів
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Записуємо ці дані у наш власний асоціативний PHP-масив
    $auth_data = [
        "Тип форми" => "Авторизація LIBRO",
        "Електронна пошта" => $email,
        "Пароль" => strlen($password) . " символів" //довжина пароля
    ];

    // Виводимо результат на веб-сторінку
    echo "<h1>Результат обробки форми входу</h1>";
    echo "<p>Дані успішно отримані сервером та збережені в PHP-масив!</p>";

    echo "<h3>Вміст створеного PHP-масиву:</h3>";
    echo "<ul>";

    foreach ($auth_data as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
} 
else {
    echo "Помилка: форму не було відправлено.";
}
