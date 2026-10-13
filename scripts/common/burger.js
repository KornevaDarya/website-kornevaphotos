import { scrollToBlock } from "../functions/scroll";

$("a[href^='#']:not([data-modal])").click(function (event) {
  event.preventDefault();
  if ($(this).attr("href") !== "#") scrollToBlock($(this).attr("href"));
});

const
  $burger = $(".burger"),
  $burgerBtn = $(".header__burger"),
  // с ноутбука меню не используется
  NOTEBOOK = window.matchMedia("(min-width: 1201px)");

function openBurger() {
  $burger.addClass("active").attr("aria-hidden", "false");
  $burgerBtn.attr("aria-expanded", "true");
  $("body").addClass("scrollOff");
}

function closeBurger() {
  if (!$burger.hasClass("active")) {
    return;
  }

  $burger.removeClass("active").attr("aria-hidden", "true");
  $burgerBtn.attr("aria-expanded", "false");
  $("body").removeClass("scrollOff");
}

$burgerBtn.on("click", openBurger);
$burger.on("click", "[data-burger-close], .burger__link, .burger__category", closeBurger);

$(document).on("keydown", function (e) {
  if (e.key === "Escape") {
    closeBurger();
  }
});

NOTEBOOK.addEventListener("change", function (e) {
  if (e.matches) {
    closeBurger();
  }
});
