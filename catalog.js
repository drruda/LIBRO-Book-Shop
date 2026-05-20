const bookGrid = document.getElementById('book-grid');
const categoryList = document.getElementById('category-list');
const catalogTitle = document.querySelector('.catalog-header h2');

// Функція для виведення карток книг на екран
function renderBooks(products) {
    bookGrid.innerHTML = "";
    
    if (products.length === 0) {
        bookGrid.innerHTML = "<p style='grid-column: 1/-1; text-align: center;'>Книг у цій категорії поки немає.</p>";
        return;
    }

    for (let i = 0; i < products.length; i++) {
        const product = products[i];
        
        // Визначаємо шлях до фото (якщо пусте в базі — беремо заглушку)
        const imgSrc = product.image ? 'upload/' + product.image : 'upload/no_image.jpg';
        
        // Гарне відображення ціни
        const priceDisplay = product.price === 0 ? "Безкоштовно / Обмін" : product.price + " ₴";

        const cardHtml = `
            <div class="book-card">
                <div class="book-image" style="background-image: url('${imgSrc}'); background-size: cover; background-position: center; height: 350px;"></div>
                <div class="book-info">
                    <span class="category">${product.category}</span>
                    <h3>${product.title}</h3>
                    <p class="author">Автор: ${product.author} <br> <span style="font-size: 0.8rem; color: #999;">Вид: ${product.publisher}</span></p>
                    <div class="card-footer">
                        <span class="price">${priceDisplay}</span>
                        <button class="buy-icon" onclick="fetchProductById(${product.id})">+</button>
                    </div>
                </div>
            </div>`;
        bookGrid.innerHTML += cardHtml;
    }
}

// Завантаження всіх товарів
async function fetchProducts() {
    catalogTitle.innerText = "Всі книги";
    const response = await fetch('get_products.php');
    const products = await response.json();
    renderBooks(products);
}

// Завантаження книг за категорією (передаємо ID жанру)
async function fetchProductsByCategory(categoryId, categoryName) {
    catalogTitle.innerText = categoryName;
    const response = await fetch('get_products.php?category=' + categoryId);
    const products = await response.json();
    renderBooks(products);
}

// Створення списку радіокнопок-жанрів у сайдбарі
async function fetchCategories() {
    const response = await fetch('get_categories.php');
    const categories = await response.json();

    categoryList.innerHTML = "";

    for (let i = 0; i < categories.length; i++) {
        const category = categories[i];

        const label = document.createElement('label');
        label.className = 'checkbox-group';
        label.style.display = 'block';
        label.innerHTML = `<input type="radio" name="genre" value="${category.id}"> ${category.name}`;

        label.addEventListener('change', function() {
            fetchProductsByCategory(category.id, category.name);
        });

        categoryList.appendChild(label);
    }
}

// Отримання опису книги по кліку на "+"
async function fetchProductById(id) {
    const response = await fetch('get_products.php?id=' + id);
    const product = await response.json();
    alert(product.title + "\n\nОпис книги:\n" + product.description);
}

// Старт при завантаженні сторінки
document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();
    fetchProducts();

    const seeAllBtn = document.getElementById('see-all');
    seeAllBtn.addEventListener('click', function(event) {
        event.preventDefault();
        fetchProducts();
        
        const radios = document.querySelectorAll('input[name="genre"]');
        for (let i = 0; i < radios.length; i++) {
            radios[i].checked = false;
        }
    });
});