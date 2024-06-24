const main = document.querySelector(".mainHeader");

window.addEventListener("scroll", () => {
  const scrollPosition = window.scrollY;
  if (scrollPosition > 10) {
    main.classList.add("scrolled");
  } else {
    main.classList.remove("scrolled");
  }
});
