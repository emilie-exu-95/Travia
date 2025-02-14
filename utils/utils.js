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

// VERIFY PASSWORD CONDITIONS, src : https://www.youtube.com/watch?v=Iss2ASrpl9s
const validationRegex = [
        { regex : /.{12,}/ }, // min length
        { regex : /[0-9]/ }, // numbers
        { regex : /[a-z]/ }, // lowercase letters
        { regex : /[A-Z]/ }, // upppercse letters
        { regex : /[^a-zA-Z0-9]/ } // special characters
]

const passChecklist = document.querySelectorAll(".pass-info");

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

// VERIFY PASSWORD STRENGTH, not the best
const passSuggestion = document.querySelector(".pass-suggestion");
const passStrength = document.getElementById("pass-strength");
const suggestion = document.getElementById("suggestion");
const strengthLevels = ["Very Weak", "Weak", "Fair", "Strong", "Very Strong"];
const strengthColors = ["#FF4C4C", "#FF4C4C", "#FF9F40", "#3DDC97", "#00C853"];
passInput.addEventListener("input", () => {
    let password = passInput.value;
    if ( !password ) { // Empty input
        passSuggestion.classList.add("hidden");
        passStrength.textContent = "";
        suggestion.textContent = "";
        return;
    }
    // Show suggestion at input
    passSuggestion.classList.remove("hidden");
    let result = zxcvbn(password);
    passStrength.textContent = strengthLevels[result.score];
    passStrength.style.color = strengthColors[result.score]
    let string = " : ";
    if (result.score > 2) {
        string = "";
    }
    suggestion.textContent = string + result.feedback.suggestions;

})