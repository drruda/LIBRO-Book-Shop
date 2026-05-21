<?php include_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додавання книги | LIBRO</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="add_book.css">
</head>

<body class="auth-page">

    <div class="auth-container">
        <div class="auth-card book-form-card">

            <div class="auth-header">
                <h2 class="logo"><a href="homePage.html">LIBRO<span>.</span></a></h2>
                <p>Додайте нову книгу в каталог</p>
            </div>

            <form name="addBook" id="bookForm" action="add_book_process.php" method="POST" enctype="multipart/form-data" novalidate>

                <div class="form-group">
                    <label for="book_title">Назва книги</label>
                    <input type="text" id="book_title" name="book_title" placeholder="Введіть назву книги">
                </div>

                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="book_author">Автор</label>
                        <input type="text" id="book_author" name="book_author" placeholder="Ім'я та прізвище">
                    </div>
                    <div class="form-group flex-1">
                        <label for="book_publisher">Видавництво</label>
                        <input type="text" id="book_publisher" name="book_publisher" placeholder="Назва">
                    </div>
                </div>

                <div class="form-group compact-group">
                    <label for="book_genre">Жанр книги:</label>
                    <select id="book_genre" name="book_genre">
                        <option value="1">Фентезі та фантастика</option>
                        <option value="2">Психологія та саморозвиток</option>
                        <option value="3">Наукова література</option>
                        <option value="4">Триллери та детективи</option>
                        <option value="5">Жахи</option>
                        <option value="6">Дитячі книги</option>
                    </select>
                </div>

                <div class="form-group compact-group">
                    <label>Стан книги:</label>
                    <div class="radio-options">
                        <input type="radio" id="state_new" name="book_state" value="Нова" checked>
                        <label for="state_new">Нова</label>

                        <input type="radio" id="state_minor" name="book_state" value="Невеликі дефекти">
                        <label for="state_minor">Дефекти</label>

                        <input type="radio" id="state_used" name="book_state" value="Вживана">
                        <label for="state_used">Вживана</label>
                    </div>
                </div>

                <div class="form-group compact-group">
                    <label>Умови ціни:</label>
                    <div class="radio-options">
                        <input type="radio" id="type_price" name="price_type" value="Певна сума" checked>
                        <label for="type_price">Сума</label>

                        <input type="radio" id="type_free" name="price_type" value="Безкоштовно">
                        <label for="type_free">Безкоштовно</label>

                        <input type="radio" id="type_exchange" name="price_type" value="Обмін">
                        <label for="type_exchange">Обмін</label>
                    </div>
                </div>

                <div class="form-group compact-group">
                    <label for="book_price">Ціна (грн):</label>
                    <input type="text" id="book_price" name="book_price" placeholder="0.00" class="price-input">
                </div>

                <div class="form-group">
                    <label for="book_image">Обкладинка книги</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="file" id="book_image" name="image" accept="image/*" required style="flex: 1;">
                        <button type="button" class="btn-register" style="padding: 8px 15px; border-radius: 8px; font-size: 0.85rem;"
                            onclick="document.getElementById('book_image').value = '';">
                            Скасувати
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="book_desc">Короткий опис книги</label>
                    <textarea id="book_desc" name="book_desc" rows="3"></textarea>
                </div>

                <p id="error" class="error-message"></p>

                <button type="submit" class="btn-auth">Додати</button>
            </form>

            <div class="auth-footer">
                <p><a href="catalog.php">Повернутися до каталогу</a></p>
            </div>

        </div>
    </div>

    <script src="addBook_validation.js"></script>
    <script src="auth.js"></script>
</body>

</html>