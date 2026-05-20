function validateForm() {
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let errorMessange = document.getElementById('error');

    // Функція для виводу помилки
    function showError(text) {
        errorMessange.textContent = text;
        errorMessange.style.display = "block";
    }

    // Перевірка на пусті поля
    if (email === '' || password === '') {
        showError("Будь ласка, заповніть усі поля!");
        return false;
    }

    // Перевірка по email
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError("Неправильний формат пошти");
        return false;
    }

    // Перевірка по password
    if (password.length < 8) {
        showError("Пароль має бути не менше 8 символів");
        return false;
    }

// Якщо все правильно — ховаємо блок
    errorMessange.style.display = "none";
    document.getElementById('loginForm').submit();
}