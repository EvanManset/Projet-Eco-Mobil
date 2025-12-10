const password = document.getElementById('password');
const toggle = document.getElementById('togglePassword');

toggle.addEventListener('click', () => {
    if (password.type === "password") {
        password.type = "text";
        toggle.textContent = "Masquer";
    } else {
        password.type = "password";
        toggle.textContent = "Afficher";
    }
});