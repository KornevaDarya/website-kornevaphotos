import {crossBrowserObserver} from "../functions/observer";

// ======== Отложенная загрузка изображений и видео ======================
let lazyMedia = function (el) {
    var $el = $(el);
    if ($el.hasClass("g-lazy-img")) {
        $el.find("img").each(function () {
            $(this).attr("src", $(this).data("src"));
        });
        $el.removeClass("g-lazy-img").find("source").each(function () {
            $(this).attr("srcset", $(this).data("src"));
        });
    } else if ($el.hasClass("g-lazy-bg")) {
        $el.removeClass("g-lazy-bg").addClass("lazy-bg-active");
    } else if ($el.hasClass("g-lazy-video")) {
        $el.removeClass("g-lazy-video").find("source").each(function () {
            $(this).attr("src", $(this).data("src"));
        });
        $el.attr("poster", $el.data("poster"));
        el.load();
    }
    $el.addClass("g-animate-play");
}

crossBrowserObserver(".g-lazy-img, .g-lazy-bg, .g-lazy-video", lazyMedia);

// ======== Перезапускаем видео ======================
let replayVideo = function (el) {
    //console.log($(el).attr("class") + " - " + el.paused);
    if (el.paused && $(el).prop("autoplay") && !$(el).hasClass("g-lazy-video")) {
        el.load();
    }
}
crossBrowserObserver("video", replayVideo, 400, false);
