const COOKIES_STORAGE_KEY = "isCookiesBannerAccepted";

if (!localStorage.getItem(COOKIES_STORAGE_KEY)) {
    $(".cookies").show();
}

$(".cookies__btn").on("click", function () {
    localStorage.setItem(COOKIES_STORAGE_KEY, "true");
    $(".cookies").hide();
});
