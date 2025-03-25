/* HANDLES FONT CHANGE DEFAULT <--> AUREBESH */

const fontToggleButton = document.getElementById("fontToggle");
const body = document.body;

fontToggleButton.addEventListener("click", () => {
    body.classList.toggle("aurebesh");
})