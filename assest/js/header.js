


let navToggle = document.querySelector(".nav-toggle");
let navMenu   = document.querySelector(".nav-menu");
let showIcon  = document.querySelector(".nav-toggle .fa-bars");
let closeIcon = document.querySelector(".nav-toggle .fa-xmark");

navToggle.addEventListener("click", () => {
        navMenu.classList.toggle("is-open");

        if (navMenu.classList.contains("is-open")) {
            showIcon.style.display  = "none";
            closeIcon.style.display = "block";
        } else {
            showIcon.style.display  = "block";
            closeIcon.style.display = "none";
        }
    });