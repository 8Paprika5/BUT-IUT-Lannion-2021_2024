const menuHamburger = document.querySelector(".menuBurger");
const navLinks = document.querySelector(".menuBurger--links");

const Link1 = document.querySelector(".link-cat1");
const Link2 = document.querySelector(".link-cat2");
const Link3 = document.querySelector(".link-cat3");

console.log(navLinks);

menuHamburger.addEventListener('click', ()=> {
    navLinks.classList.toggle('mobile-menu');
})

Link1.addEventListener('click', ()=> {
    navLinks.classList.toggle('mobile-menu');
})

Link2.addEventListener('click', ()=> {
    navLinks.classList.toggle('mobile-menu');
})

Link3.addEventListener('click', ()=> {
    navLinks.classList.toggle('mobile-menu');
})