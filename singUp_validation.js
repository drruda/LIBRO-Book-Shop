let errorMessage;
let formElement;

document.addEventListener('DOMContentLoaded', () => {
    formElement = document.getElementById('registerForm');
    errorMessage = document.getElementById('error');
    const registerButton = document.getElementById('registerButton');
    if (formElement) {
        formElement.addEventListener('submit', event => event.preventDefault());
    }
    if (registerButton) {
        registerButton.addEventListener('click', handleSignUpSubmit);
    }
    window.handleSignUpSubmit = handleSignUpSubmit;
});

async function handleSignUpSubmit(event) {
    if (event) event.preventDefault();
    if (!formElement) {
        alert('Помилка реєстрації: не знайдено форму. Спробуйте оновити сторінку.');
        return false;
    }

    if (errorMessage) {
        errorMessage.style.display = 'none';
    }

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const terms = document.getElementById('terms');

    const errorText = validateFormData(name, email, password, terms);
    if (errorText) {
        showError(errorText);
        return false;
    }

    try {
        const formData = new FormData(formElement);
        const response = await fetch(formElement.action, {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.message || 'Невідома помилка');
        }

        alert('Реєстрація успішна!');
        formElement.reset();
        return false;
    } catch (error) {
        alert('Помилка реєстрації: ' + error.message);
        return false;
    }
}

function showError(text) {
    if (!errorMessage) return;
    errorMessage.textContent = text;
    errorMessage.style.display = 'block';
}

function validateFormData(name, email, password, terms) {
    if (name === '' || email === '' || password === '') {
        return 'Будь ласка, заповніть усі поля!';
    }

    const namePattern = /^[a-zA-Zа-яА-ЯіїєґІЇЄҐ]+\s[a-zA-Zа-яА-ЯіїєґІЇЄҐ]+$/;
    if (!namePattern.test(name)) {
        return 'Введіть своє прізвище та ім'я через пробіл';
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.com$/;
    if (!emailPattern.test(email)) {
        return 'Неправильний формат пошти';
    }

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
    if (!passwordPattern.test(password)) {
        return 'Пароль має бути не менше 8 символів та містити велику літеру, малу літеру та спецсимвол';
    }

    if (!terms.checked) {
        return 'Ви повинні погодитися з умовами';
    }

    return '';
}
