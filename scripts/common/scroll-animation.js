import { crossBrowserObserver } from "../functions/observer";

setTimeout(function () {
  let animateOnScroll = function (el) {
    var $el = $(el);
    if (!$el.hasClass("active")) {
      $el.addClass("active");
    }
  };

  crossBrowserObserver(".scroll-animate", animateOnScroll, 80);
}, 500);
