const passInput = document.getElementById("pswd");
const showPass = document.querySelector(".fa-eye");
const hidePass = document.querySelector(".fa-eye-slash");

showPass.addEventListener("click", () => {
    passInput.type = "text";
    hidePass.classList.remove("hidden");
    showPass.classList.add("hidden");
})

hidePass.addEventListener("click", () => {
    passInput.type = "password";
    hidePass.classList.add("hidden");
    showPass.classList.remove("hidden");
})