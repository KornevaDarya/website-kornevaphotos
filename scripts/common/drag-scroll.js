// Скроллбар у лент скрыт, а колесом мыши горизонтально не прокрутить,
// поэтому на десктопе даём таскать содержимое курсором.

const DRAG_SCROLL_SELECTOR = ".provider__list, .products__tabs";

function initDragScroll(el) {
    let
        isDown = false,
        startX = 0,
        startScroll = 0,
        moved = false;

    // подсказываем курсором, только когда есть что листать
    function refreshState() {
        el.classList.toggle("is-draggable", el.scrollWidth > el.clientWidth);
    }

    function stopDrag() {
        if (!isDown) {
            return;
        }
        isDown = false;
        el.classList.remove("is-dragging");
    }

    el.addEventListener("mousedown", function (e) {
        if (e.button !== 0 || el.scrollWidth <= el.clientWidth) {
            return;
        }

        isDown = true;
        moved = false;
        startX = e.pageX;
        startScroll = el.scrollLeft;
        el.classList.add("is-dragging");
        e.preventDefault();
    });

    document.addEventListener("mousemove", function (e) {
        if (!isDown) {
            return;
        }

        const shift = e.pageX - startX;

        if (Math.abs(shift) > 3) {
            moved = true;
        }

        el.scrollLeft = startScroll - shift;
    });

    document.addEventListener("mouseup", stopDrag);

    el.addEventListener("click", function (e) {
        if (!moved) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();
        moved = false;
    }, true);

    refreshState();
    $(window).on("resize", refreshState);
}

$(function () {
    document.querySelectorAll(DRAG_SCROLL_SELECTOR).forEach(initDragScroll);
});
