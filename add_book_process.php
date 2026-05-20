<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Збираємо дані з форми
    $book_title = htmlspecialchars($_POST['book_title']);
    $book_author = htmlspecialchars($_POST['book_author']);
    $book_publisher = htmlspecialchars($_POST['book_publisher']);
    $book_genre = htmlspecialchars($_POST['book_genre']);
    $book_state = htmlspecialchars($_POST['book_state']);
    $price_type = htmlspecialchars($_POST['price_type']);
    $book_desc = htmlspecialchars($_POST['book_desc']);

    // Якщо обрано "Безкоштовно" або "Обмін", то замість цифри записуємо відповідний текст
    $book_price = htmlspecialchars($_POST['book_price']);
    if ($price_type === "Безкоштовно") {
        $final_price = "Безкоштовно (в добрі руки)";
    } elseif ($price_type === "Обмін") {
        $final_price = "Обмін на іншу книгу";
    } else {
        $final_price = empty($book_price) ? "0.00 грн" : $book_price . " грн";
    }

    // Записуємо всі дані в масив
    $book_data = [
        "Тип форми" => "Додавання нової книги в каталог LIBRO",
        "Назва книги" => $book_title,
        "Автор" => $book_author,
        "Видавництво" => $book_publisher,
        "Жанр" => $book_genre,
        "Стан книги" => $book_state,
        "Умови розповсюдження" => $price_type,
        "Кінцева ціна" => $final_price,
        "Короткий опис" => empty($book_desc) ? "Опис відсутній" : $book_desc
    ];

    // Виводимо результат на веб-сторінку
    echo "<h1>Результат обробки форми додавання книги</h1>";
    echo "<p>Книгу успішно оброблено сервером та внесено в PHP-масив!</p>";

    echo "<h3>Вміст створеного PHP-масиву:</h3>";
    echo "<ul>";
    foreach ($book_data as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
} 
else {
    echo "Помилка: форму не було відправлено.";
}
