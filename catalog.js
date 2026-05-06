// Пошук елементів на сторінці
const bookGrid = document.querySelector('.book-grid');
const categoryList = document.getElementById('category-list');
const catalogTitle = document.querySelector('.catalog-header h2');

// Функція для отримання та виведення всіх товарів
async function fetchProducts() {
    catalogTitle.innerText = "Всі товари";
    
    const response = await fetch('https://dummyjson.com/products');
    const data = await response.json();
    const products = data.products;

    bookGrid.innerHTML = "";

    for (let i = 0; i < products.length; i++) {
        const product = products[i];
        const cardHtml = `
            <div class="book-card">
                <div class="book-image" style="background-image: url('${product.thumbnail}'); background-size: cover;"></div>
                <div class="book-info">
                    <span class="category">${product.category}</span>
                    <h3>${product.title}</h3>
                    <p class="author">${product.brand}</p>
                    <div class="card-footer">
                        <span class="price">${product.price} ₴</span>
                        <button class="buy-icon" onclick="fetchProductById(${product.id})">+</button>
                    </div>
                </div>
            </div>`;
        bookGrid.innerHTML = bookGrid.innerHTML + cardHtml;
    }
}

// Функція для завантаження товарів конкретної категорії
async function fetchProductsByCategory(categoryName) {
    catalogTitle.innerText = categoryName;

    const response = await fetch('https://dummyjson.com/products/category/' + categoryName);
    const data = await response.json();
    const products = data.products;

    bookGrid.innerHTML = "";

    for (let i = 0; i < products.length; i++) {
        const product = products[i];
        const cardHtml = `
            <div class="book-card">
                <div class="book-image" style="background-image: url('${product.thumbnail}'); background-size: cover;"></div>
                <div class="book-info">
                    <span class="category">${product.category}</span>
                    <h3>${product.title}</h3>
                    <p class="author">${product.brand}</p>
                    <div class="card-footer">
                        <span class="price">${product.price} ₴</span>
                        <button class="buy-icon" onclick="fetchProductById(${product.id})">+</button>
                    </div>
                </div>
            </div>`;
        bookGrid.innerHTML = bookGrid.innerHTML + cardHtml;
    }
}

// Функція для створення списку категорій
async function fetchCategories() {
    const response = await fetch('https://dummyjson.com/products/categories');
    const categories = await response.json();

    categoryList.innerHTML = "";

    for (let i = 0; i < 10; i++) {
        const category = categories[i];
        const slug = category.slug;
        const name = category.name;

        const label = document.createElement('label');
        label.className = 'checkbox-group';
        label.style.display = 'block';
        label.innerHTML = '<input type="radio" name="genre" value="' + slug + '"> ' + name;

        label.addEventListener('change', function() {
            fetchProductsByCategory(slug);
        });

        categoryList.appendChild(label);
    }
}

// Функція для отримання даних одного товару
async function fetchProductById(id) {
    const response = await fetch('https://dummyjson.com/products/' + id);
    const product = await response.json();
    alert(product.title + " - " + product.description);
}

// Запуск коду після завантаження сторінки та налаштування кнопок
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