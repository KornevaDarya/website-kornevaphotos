export function crossBrowserObserver(selector, callback, margin = 1400, unobserve = true) {
    if (!("IntersectionObserver" in window) || !("IntersectionObserverEntry" in window) || !("intersectionRatio" in window.IntersectionObserverEntry.prototype)) {
        //console.log("lazy scroll");
        $(window).scroll(function () {
            var scrollTop = $(window).scrollTop();
            $(selector).each(function () {
                if (scrollTop >= ($(this).offset().top - margin)) {
                    callback(this);
                }
            });
        });
    } else {
        //console.log("lazy observer");
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.intersectionRatio > 0) {
                    callback(entry.target);
                    if (unobserve === true) {
                        observer.unobserve(entry.target);
                    }
                }
            });
        }, {
            rootMargin: `${margin}px 0px`
        });

        $(selector).each(function (index, element) {
            observer.observe(element);
        });
    }
}
