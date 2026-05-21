<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0); // Захист від ламання JSON тексту помилок

$host = "localhost";
$port = "5432";
$dbname = "Libro_db";
$user = "postgres";
$password = "lex0512";

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$db_connect = pg_connect($connection_string);

if (!$db_connect) {
    echo json_encode(["success" => false, "message" => "База даних недоступна"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// 1. ВИДАЛЕННЯ КНИГИ
if ($method === 'DELETE') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Видаляємо обкладинку
        $img_query = "SELECT image FROM products WHERE productsid = $1";
        $img_res = pg_query_params($db_connect, $img_query, array($id));
        if ($img_res && $row = pg_fetch_assoc($img_res)) {
            $img = $row['image'];
            if ($img && $img !== 'no_image.jpg' && file_exists(__DIR__ . '/upload/' . $img)) {
                unlink(__DIR__ . '/upload/' . $img);
            }
        }

        $query = "DELETE FROM products WHERE productsid = $1";
        $result = pg_query_params($db_connect, $query, array($id));

        if ($result) {
            echo json_encode(["success" => true, "message" => "Книгу видалено"]);
        } else {
            echo json_encode(["success" => false, "message" => "Помилка видалення з бази"]);
        }
    }
    pg_close($db_connect);
    exit;
}

// 2. ОНОВЛЕННЯ ДАНИХ (POST)
if ($method === 'POST') {
    // Дані приходять через стандартний $_POST завдяки FormData
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $author = htmlspecialchars($_POST['author']);
        $publisher = htmlspecialchars($_POST['publisher']);
        $price = floatval($_POST['price']);
        $desc = htmlspecialchars($_POST['description']);

        $image_sql_part = "";
        $params = array($title, $author, $publisher, $price, $desc, $id);

        // Обробка завантаження нового фото, якщо воно було прикріплене
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpName = $_FILES['image']['tmp_name'];
            $image_info = getimagesize($fileTmpName);

            if ($image_info !== false) {
                $upload_dir = __DIR__ . '/upload/';

                // Видаляємо стару унікальну обкладинку з папки
                $old_img_query = "SELECT image FROM products WHERE productsid = $1";
                $old_img_res = pg_query_params($db_connect, $old_img_query, array($id));
                if ($old_img_res && $row = pg_fetch_assoc($old_img_res)) {
                    $old_img = $row['image'];
                    if ($old_img && $old_img !== 'no_image.jpg' && file_exists($upload_dir . $old_img)) {
                        unlink($upload_dir . $old_img);
                    }
                }

                // Генеруємо нове ім'я
                $name = uniqid('prod_');
                $extension = image_type_to_extension($image_info[2]);
                $format = str_replace('jpeg', 'jpg', $extension);
                $final_file_name = $name . $format;

                if (move_uploaded_file($fileTmpName, $upload_dir . $final_file_name)) {
                    $image_sql_part = ", image = $7";
                    $params[] = $final_file_name;
                }
            }
        }

        $query = "UPDATE products SET title = $1, author = $2, publisher = $3, price = $4, description = $5 $image_sql_part WHERE productsid = $6";
        $result = pg_query_params($db_connect, $query, $params);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Дані книги та обкладинку успішно оновлено!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Помилка оновлення бази даних: " . pg_last_error($db_connect)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Некоректні дані форми"]);
    }
    pg_close($db_connect);
    exit;
}
