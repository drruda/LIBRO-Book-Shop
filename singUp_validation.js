function validateForm() {
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let terms = document.getElementById('terms');
    let errorMessange = document.getElementById('error');

    // Функція для виводу помилки
    function showError(text) {
        errorMessange.textContent = text;
        errorMessange.style.display = "block"; // ПРИМУСОВО ПОКАЗУЄМО БЛОК
    }

    // Перевірка на пусті поля
    if (name === '' || email === '' || password === '') {
        showError("Будь ласка, заповніть усі поля!");
        return false;
    }

    // Перевірка по name
    let namePattern = /^[a-zA-Zа-яА-ЯіїєґІЇЄҐ]+\s[a-zA-Zа-яА-ЯіїєґІЇЄҐ]+$/; // регулярний вираз: тільки букви (укр/англ), один пробіл між двома словами
    if (!namePattern.test(name)) { 
        showError("Введіть своє прізвище та ім'я через пробіл");
        return false;
    }

    // Перевірка по email
    let emailPattern = /^[^\s@]+@[^\s@]+\.com$/;
    if (!emailPattern.test(email)) {
        showError("Неправильний формат пошти");
        return false;
    }

    // Перевірка по password
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
    if (!passwordPattern.test(password)) {
        showError("Пароль має бути не менше 8 символів та містити велику літеру, малу літеру та спецсимвол");
        return false;
    }

    // Перевірка чекбокса
    if (!terms.checked) {
        showError("Ви повинні погодитися з умовами");
        return false;
    }

// Якщо все правильно — ховаємо блок
    errorMessange.style.display = "none";
    return true;
}