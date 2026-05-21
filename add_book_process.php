<?php
// Функція генерації унікального ID для фото
function getRandomFileName($path)
{
    $path = $path ? rtrim($path, '/') . '/' : '';
    do {
        $name = uniqid('prod_');
        $file = $path . $name;
    } while (file_exists($file));

    return $name;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. ОБРОБКА ТА ЗАВАНТАЖЕННЯ ФОТО
    $saved_image_name = "no_image.jpg"; // Назва за замовчуванням

    if (isset($_FILES['image'])) {
        $fileTmpName = $_FILES['image']['tmp_name'];
        $errorCode = $_FILES['image']['error'];

        if ($errorCode === UPLOAD_ERR_OK && is_uploaded_file($fileTmpName)) {

            // Перевірка, що це дійсно зображення
            $fi = finfo_open(FILEINFO_MIME_TYPE);
            $mime = (string) finfo_file($fi, $fileTmpName);
            if (strpos($mime, 'image') === false) {
                die('Помилка: Можна завантажувати лише зображення.');
            }

            // Перевірка розміру (макс. 5 МБ)
            $image_info = getimagesize($fileTmpName);
            $limitBytes = 1024 * 1024 * 5;
            if (filesize($fileTmpName) > $limitBytes) {
                die('Помилка: Розмір зображення не повинен перевищувати 5 Мбайт.');
            }

            // Генерація унікального імені файлу
            $upload_dir = __DIR__ . '/upload/';

            // Створюємо папку upload, якщо її ще немає
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $name = getRandomFileName($upload_dir);
            $extension = image_type_to_extension($image_info[2]);
            $format = str_replace('jpeg', 'jpg', $extension);

            $final_file_name = $name . $format;

            // Переміщуємо фото в папку upload
            if (move_uploaded_file($fileTmpName, $upload_dir . $final_file_name)) {
                $saved_image_name = $final_file_name;
            } else {
                die('Помилка: Не вдалося зберегти файл на сервері.');
            }
        }
    }

    // 2. ЗБИРАННЯ ТА ОЧИЩЕННЯ ДАНИХ З ФОРМИ
    $title = htmlspecialchars($_POST['book_title']);
    $author = htmlspecialchars($_POST['book_author']);
    $publisher = htmlspecialchars($_POST['book_publisher']);
    $categoryID = intval($_POST['book_genre']); // Перетворюємо в число ID категорії
    $book_desc = htmlspecialchars($_POST['book_desc']);
    $price_type = htmlspecialchars($_POST['price_type']);
    $book_price = $_POST['book_price'];

    // Розрахунок фінальної ціни для бази даних (числове поле)
    if ($price_type === "Безкоштовно" || $price_type === "Обмін") {
        $final_price = 0.00; // Для обміну або безкоштовних книг ставимо 0
    } else {
        $final_price = floatval(str_replace(',', '.', $book_price));
    }

    if (empty($book_desc)) {
        $book_desc = "Опис відсутній";
    }

    // 3. ПІДКЛЮЧЕННЯ ДО БАЗИ ДАНИХ POSTGRESQL
    $host = "localhost";
    $port = "5432";
    $dbname = "Libro_db";
    $user = "postgres";
    $password = "lex0512";

    $connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $db_connect = pg_connect($connection_string);

    if (!$db_connect) {
        die("Помилка підключення до бази даних PostgreSQL.");
    }

    // 4. ЗАПИС ДАНИХ У ТАБЛИЦЮ PRODUCTS
    $query = "INSERT INTO products (title, author, publisher, categoryID, image, price, description) 
              VALUES ($1, $2, $3, $4, $5, $6, $7)";

    $result = pg_query_params($db_connect, $query, array(
        $title,
        $author,
        $publisher,
        $categoryID,
        $saved_image_name,
        $final_price,
        $book_desc
    ));

    // 5. ПЕРЕВІРКА РЕЗУЛЬТАТУ
    if ($result) {
        echo "<h1>Успіх!</h1>";
        echo "<p>Книгу «<strong>$title</strong>» успішно додано до бази даних PostgreSQL та збережено обкладинку!</p>";
        echo "<p><a href='catalog.php'>Повернутися до каталогу</a></p>";
    } else {
        echo "<h1>Помилка!</h1>";
        echo "<p>Не вдалося внести книгу в базу даних: " . pg_last_error($db_connect) . "</p>";
    }

    // Закриваємо з'єднання
    pg_close($db_connect);
} else {
    echo "Помилка: форму не було відправлено.";
}
