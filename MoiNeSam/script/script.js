const login = document.getElementsByName("login")[0];
const password = document.getElementsByName("password")[0];
const error = document.querySelector(".error");
console.log(document.querySelector("button"));
document.querySelector("button").addEventListener("click", (event) => {
    event.preventDefault();
    const loginValue = login.value.trim();
    const passwordValue = password.value.trim();

    if (loginValue === "" || passwordValue === "") {
        error.textContent = "Заполните поля";
    } else {
        error.textContent = "";
    }
});