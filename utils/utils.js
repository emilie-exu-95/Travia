/*
UTILS.JS CONTAINS
    - Password eye toggle to show/hide value
    - Password validity
 */

// SHOW PASSWORD
const passInput = document.getElementById("pass");
const showPass = document.querySelector(".fa-eye");
const hidePass = document.querySelector(".fa-eye-slash");

showPass.addEventListener("click", () => {
    showPass.classList.toggle("fa-eye");
    showPass.classList.toggle("fa-eye-slash");
    passInput.type = passInput.type == "password" ? "text" : "password";
})

// VERIFY PASSWORD STRENGTH, src : https://www.youtube.com/watch?v=Iss2ASrpl9s
const validationRegex = [
        { regex : /.{12,}/ }, // min length
        { regex : /[0-9]/ }, // numbers
        { regex : /[a-z]/ }, // lowercase letters
        { regex : /[A-Z]/ }, // upppercse letters
        { regex : /[^a-zA-Z0-9]/ } // special characters
]

let passChecklist = document.querySelectorAll(".pass-info");

passInput.addEventListener("keyup", () => {
    validationRegex.forEach((item, i) => {
        let isValid = item.regex.test(passInput.value);

        if( isValid ) {
            passChecklist[i].classList.add("checked");
        } else {
            passChecklist[i].classList.remove("checked");
        }
    })
})