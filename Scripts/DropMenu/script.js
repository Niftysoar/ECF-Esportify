const burger = document.querySelector(".burger");
const burgerIcon = document.querySelector(".burger i");
const dropDownMenu = document.querySelector(".dropdown-menu");

burger.onclick = function () {
  dropDownMenu.classList.toggle('open')
  const isOpen = dropDownMenu.classList.contains('open')

  burgerIcon.classList = isOpen
    ? 'fa-solid fa-xmark'
    : 'fa-solid fa-bars'
}