const tableBody = document.getElementById('admin-table-body');
const editSection = document.getElementById('edit-section');
const editForm = document.getElementById('adminEditForm');
const alertMessage = document.getElementById('alert-message');

// Завантаження всіх книг у таблицю з бази
async function loadAdminTable() {
    const response = await fetch('get_products.php');
    const products = await response.json();
    
    tableBody.innerHTML = "";
    
    products.forEach(product => {
        const imgSrc = product.image ? 'upload/' + product.image : 'upload/no_image.jpg';
        
        const rowHtml = `
            <tr id="row-${product.id}">
                <td><strong>${product.id}</strong></td>
                <td><img src="${imgSrc}" width="40" height="55" style="object-fit: cover; border-radius: 4px;"></td>
                <td>${product.title}</td>
                <td>${product.author}</td>
                <td>${product.publisher}</td>
                <td><span style="font-size:0.8rem; background:#eef0ed; padding:3px 8px; border-radius:12px;">${product.category}</span></td>
                <td><strong>${product.price} ₴</strong></td>
                <td>
                    <button class="btn-table-edit" onclick="startEdit(${product.id}, '${escapeHtml(product.title)}', '${escapeHtml(product.author)}', '${escapeHtml(product.publisher)}', ${product.price})">Редагувати</button>
                    <button class="btn-table-delete" onclick="deleteBook(${product.id})">Видалити</button>
                </td>
            </tr>`;
        tableBody.innerHTML += rowHtml;
    });
}

async function startEdit(id, title, author, publisher, price) {
    editSection.style.display = "block";
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    document.getElementById('edit-book-id').innerText = id;
    document.getElementById('form_book_id').value = id;
    document.getElementById('form_title').value = title;
    document.getElementById('form_author').value = author;
    document.getElementById('form_publisher').value = publisher;
    document.getElementById('form_price').value = price;

    // Завантажуємо поточний опис книги через існуюче API
    const response = await fetch(`get_products.php?id=${id}`);
    const data = await response.json();
    document.getElementById('form_desc').value = data.description;
}

// Надсилання оновлених даних форми в API
editForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('id', document.getElementById('form_book_id').value);
    formData.append('title', document.getElementById('form_title').value);
    formData.append('author', document.getElementById('form_author').value);
    formData.append('publisher', document.getElementById('form_publisher').value);
    formData.append('price', document.getElementById('form_price').value);
    formData.append('description', document.getElementById('form_desc').value);
    
    // Перевіряємо, чи вибрано новий файл обкладинки
    const imageInput = document.getElementById('form_image');
    if (imageInput && imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    const response = await fetch('admin_api.php', {
        method: 'POST',
        body: formData // Передаємо FormData
    });
    
    const result = await response.json();
    if (result.success) {
        showStatus(result.message, "green");
        editSection.style.display = "none";
        if (imageInput) imageInput.value = ""; // Очищаємо поле файлу
        loadAdminTable(); // Перезавантажуємо таблицю
    } else {
        showStatus(result.message, "red");
    }
});

// Видалення книги
async function deleteBook(id) {
    if (confirm("Ви впевнені, що хочете видалити цю книгу з бази?")) {
        const response = await fetch(`admin_api.php?id=${id}`, { method: 'DELETE' });
        const result = await response.json();
        
        if (result.success) {
            showStatus("Книгу успішно видалено!", "green");
            const row = document.getElementById(`row-${id}`);
            if (row) row.remove();
        } else {
            showStatus(result.message, "red");
        }
    }
}

// Службові функції
document.getElementById('cancel-edit').addEventListener('click', () => editSection.style.display = "none");

function showStatus(text, color) {
    alertMessage.innerHTML = `<p style="color: ${color}; font-weight: bold; margin-bottom: 20px;">${text}</p>`;
}

// Функція безпечного екранування тексту для onclick
function escapeHtml(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, "&quot;");
}

const fileInput = document.getElementById('form_image');
const selectedFileName = document.getElementById('selected-file-name');
if (fileInput && selectedFileName) {
    fileInput.addEventListener('change', () => {
        selectedFileName.textContent = fileInput.files.length > 0
            ? fileInput.files[0].name
            : 'Файл не обрано';
    });
}

document.addEventListener('DOMContentLoaded', loadAdminTable);