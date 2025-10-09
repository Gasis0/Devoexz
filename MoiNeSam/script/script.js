const login=document.getElementsByName("login;password");
const password=document.getElementsByName("password");
const error=document.getElementsByName("error");
document.querySelector("button").addEventListener("click", (event) =>{
    error.innerHTML="Заполнитне поля";
    event.preventDefault();
}

)