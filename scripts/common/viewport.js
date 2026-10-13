var
    vpChanged = false,
    weInChange = false;

function setViewport() {
    if (!weInChange) {
        weInChange = true;

        let windowWidth = Math.min($(window).width(), window.screen.width),
            minWidth = 400,
            ratio = windowWidth / minWidth;

        if (windowWidth < minWidth) {
            let content = `viewport-fit=cover, initial-scale=${ratio}, maximum-scale=${ratio}, minimum-scale=${ratio}, user-scalable=no, width=${windowWidth}`;
            $(`[name="viewport"]`).attr("content", content);
            //Cookies.set("viewport", content, {expires: 365, path: "/", domain: cookieDomain});
            vpChanged = true;
        } else if (vpChanged) {
            $(`[name="viewport"]`).attr("content", "viewport-fit=cover, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, width=device-width");
            //Cookies.remove("viewport");
            vpChanged = false;
        }

        setTimeout(function () {
            weInChange = false;
        }, 100);
    }
}

function setVH() {
    let vh = window.innerHeight * 0.01;
    if (window.visualViewport && (typeof typeof window.visualViewport != "undefined")) {
        vh = window.visualViewport.height * 0.01;
    }
    document.documentElement.style.setProperty("--vh", `${vh}px`);
}

function setHeaderHeight() {
    let header = document.querySelector(".header");
    if (header) {
        document.documentElement.style.setProperty("--header-height", `${header.offsetHeight}px`);
    }
}

function setSizeVariables() {
    setViewport();
    setVH();
    setHeaderHeight();
}

setSizeVariables();
$(window).resize(function() {
    setTimeout(function() {
        setSizeVariables();
    }, 100);
});
$(window).on("load", function() {
    setSizeVariables();
});
