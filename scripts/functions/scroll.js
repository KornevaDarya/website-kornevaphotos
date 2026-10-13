export function scrollToBlock(selector, speed = 250) {
    let offsetTop = 0;

    if (selector === "#top") {
        selector = "html";
    } else {
        selector = selector.replace("#", ".");
        offsetTop = $(".header").innerHeight();
    }

    $(window).queue([]).scrollTo(selector, speed, {
        offset: {
            top: -offsetTop
        }
    });
}