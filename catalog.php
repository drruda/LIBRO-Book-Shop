<?php include_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIBRO | Каталог</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="catalog.css">
</head>

<body>
    <header>
        <div class="container header-flex">
            <div class="logo"><a href="homePage.html">LIBRO<span>.</span></a></div>
            <nav>
                <ul class="nav-links">
                    <li><a href="homePage.html">Головна</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="offers.php">Пропозиції</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="logIn.html" class="btn-login">Увійти</a>
                <a href="signUp.html" class="btn-register">Зареєструватися</a>
            </div>
        </div>
    </header>

    <main class="container catalog-page">
        <aside class="filters">
            <h3>Фільтри</h3>
            <h4 class="filter-group">Жанри</h4>

            <div id="category-list"></div>
            <button id="see-all" class="btn-auth" style="margin-top: 15px; width: 100%;">Скинути фільтри</button>
            <a href="add_book.php" class="btn-auth" style="display: block; margin-top: 15px; text-decoration: none; text-align: center; box-sizing: border-box;">Додати власну книгу</a>
        </aside>

        <section class="catalog-content">
            <div class="catalog-header">
                <h2>Всі книги</h2>
                <form class="search-form" onsubmit="return false;">
                    <input type="search" id="catalogSearch" class="search-input" placeholder="Пошук за назвою або автором">
                </form>
            </div>

            <div class="book-grid" id="book-grid"></div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 Libro Books. Твій затишний куточок для читання.</p>
        </div>
    </footer>

    <script src="catalog.js"></script>
    <script src="auth.js"></script>
</body>

</html>