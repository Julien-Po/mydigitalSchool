const main = document.querySelector(".mainHeader");
const menuBtn = document.querySelector(".menuBtn");

window.addEventListener("scroll", () => {
  const scrollPosition = window.scrollY;
  if (scrollPosition > 10) {
    main.classList.add("scrolled");
  } else {
    main.classList.remove("scrolled");
  }
});

menuBtn.addEventListener("click", () => {
  main.classList.toggle("menu-open");
});
