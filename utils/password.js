/*
PASSWORD.JS CONTAINS
    - Password eye toggle to show/hide value
    - Password validity
 */

// SHOW PASSWORD
const passInput = document.getElementById("password");
const showPass = document.querySelector(".eye-icon");
const hidePass = document.querySelector(".slashed-eye-icon");

function togglePassword() {
    // Toggle password visibility
    passInput.type = passInput.type === "password" ? "text" : "password";
    // Toggle eye icon
    showPass.classList.toggle("hidden");
    hidePass.classList.toggle("hidden");
}

// VERIFY PASSWORD CONDITIONS
const validationRegex = [
        { regex: /.{12,}/ }, // min length, 12 characters
        { regex: /[0-9]/ }, // number
        { regex: /[a-z]/ }, // lowercase constter
        { regex: /[A-Z]/ }, // uppercase constter
        { regex: /[^a-zA-Z0-9]/ } // special character
]

const passChecklist = document.querySelectorAll("#pass-checklist .small-list-item");
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

// VERIFY PASSWORD STRENGTH
const passSuggestion = document.querySelector(".pass-suggestion");
const passStrength = document.getElementById("pass-strength");
const suggestion = document.getElementById("suggestion");
const strengthLevels = ["Very Weak", "Weak", "Fair", "Strong", "Very Strong"];
const strengthColors = ["#FF4C4C", "#FF4C4C", "#FF9F40", "#3DDC97", "#00C853"];
passInput.addEventListener("input", () => {
    let password = passInput.value;
    if ( !password ) { // Empty input
        passSuggestion.classList.add("invisible");
        passStrength.textContent = "";
        suggestion.textContent = "";
        return;
    }
    // Show suggestion at input
    passSuggestion.classList.remove("invisible");
    let result = zxcvbn(password);
    passStrength.textContent = strengthLevels[result.score];
    passStrength.style.color = strengthColors[result.score]
    let string = " : ";
    if (result.score > 2) {
        string = "";
    }
    suggestion.textContent = string + result.feedback.suggestions;

})