const header = document.querySelector(".mainHeader");

window.addEventListener("scroll", () => {
  const scrollPosition = window.scrollY;
  if (scrollPosition > 10) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});