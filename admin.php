<?php include_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Адміністратора | LIBRO</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body class="admin-page">

    <header>
        <div class="container header-flex">
            <h2 class="logo"><a href="homePage.html">LIBRO<span>.</span></a></h2>
            <p class="admin-badge">Режим Адміністратора (CRUD)</p>
        </div>
    </header>

    <main class="container admin-box">
        <h1 style="margin: 20px;">Управління каталогом товарів</h1>
        <div id="alert-message"></div>

        <section id="edit-section" style="display: none;">
            <div class="edit-form-card">
                <h3>Редагування книги (ID: <span id="edit-book-id"></span>)</h3>
                <form id="adminEditForm" novalidate>
                    <input type="hidden" id="form_book_id">

                    <div class="form-group">
                        <label for="form_title">Назва книги</label>
                        <input type="text" id="form_title" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label for="form_author">Автор</label>
                            <input type="text" id="form_author" required>
                        </div>
                        <div class="form-group flex-1">
                            <label for="form_publisher">Видавництво</label>
                            <input type="text" id="form_publisher" required>
                        </div>
                    </div>

                    <div class="form-row" style="display: flex; gap: 20px;">
                        <div class="form-group" style="width: 150px;">
                            <label for="form_price">Ціна (₴)</label>
                            <input type="number" step="0.01" id="form_price" required>
                        </div>

                        <div class="form-group" style="flex: 1; margin-left: 30px;">
                            <label for="form_image">Нова обкладинка</label>
                            <div class="file-field">
                                <input type="file" id="form_image" accept="image/*" hidden>
                                <label for="form_image" class="btn-register file-select-btn">Вибрати файл</label>
                                <span id="selected-file-name">Файл не обрано</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="form_desc">Короткий опис</label>
                        <textarea id="form_desc" rows="8"></textarea>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn-auth" style="width: auto; padding: 10px 25px;">Зберегти
                            зміни</button>
                        <button type="button" id="cancel-edit" class="btn-register">Скасувати</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="table-responsive">
            <h3>Список усіх книг у базі даних</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Обкладинка</th>
                        <th>Назва книги</th>
                        <th>Автор</th>
                        <th>Видавництво</th>
                        <th>Жанр</th>
                        <th>Ціна</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody id="admin-table-body">
                </tbody>
            </table>
        </section>
    </main>

    <script src="admin.js"></script>
    <script src="auth.js"></script>
</body>

</html>