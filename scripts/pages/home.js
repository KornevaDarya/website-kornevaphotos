// Слайдер в галерее на главной
function initGallery() {
    const
        $viewport = $(".gallery__slider"),
        $track = $(".gallery__track");

    if (!$viewport.length) {
        return;
    }

    const
        $slides = $track.children(),
        $prev = $(".gallery__nav--prev"),
        $next = $(".gallery__nav--next");

    let index = 0;

    // шаг слайда с учётом отступа между кадрами
    const slideWidth = () => $slides.first().outerWidth(true);
    // сколько кадров сейчас видно в области слайдера
    const maxIndex = () => Math.max(0, $slides.length - Math.max(1, Math.round($viewport.width() / slideWidth())));

    function update() {
        index = Math.min(index, maxIndex());

        $track.css("transform", "translateX(" + (-index * slideWidth()) + "px)");
        $prev.prop("disabled", index === 0).toggleClass("gallery__nav--disabled", index === 0);
        $next.prop("disabled", index === maxIndex()).toggleClass("gallery__nav--disabled", index === maxIndex());
    }

    function slidePrev() {
        if (index > 0) {
            index--;
            update();
        }
    }

    function slideNext() {
        if (index < maxIndex()) {
            index++;
            update();
        }
    }

    $prev.on("click", slidePrev);
    $next.on("click", slideNext);
    $(window).on("resize", update);

    // прокрутка свайпом на тач-экранах
    let touchStartX = null;

    $viewport
        .on("touchstart", (e) => {
            touchStartX = e.originalEvent.touches[0].clientX;
        })
        .on("touchend", (e) => {
            if (touchStartX === null) {
                return;
            }

            const deltaX = e.originalEvent.changedTouches[0].clientX - touchStartX;

            if (Math.abs(deltaX) > 50) {
                deltaX < 0 ? slideNext() : slidePrev();
            }

            touchStartX = null;
        });

    update();
}

// Форма бронирования
function initBookingForm() {
    const $form = $(".booking__form");

    if (!$form.length) {
        return;
    }

    const
        $comment = $form.find(".request__input-comment"),
        $photo = $form.find("[name=photo]"),
        $date = $form.find("[name=date]"),
        $message = $form.find("[name=message]");

    $photo.select2({
        minimumResultsForSearch: Infinity,
        width: "100%",
        dropdownParent: $form
    });

    function composeComment() {
        const parts = [];

        if ($photo.val()) {
            parts.push("Тип съёмки: " + $photo.find("option:selected").text().trim());
        }

        if ($date.val()) {
            parts.push("Дата: " + $date.val().split("-").reverse().join("."));
        }

        if ($message.val().trim()) {
            parts.push("Сообщение: " + $message.val().trim());
        }

        $comment.val(parts.join("; "));
    }

    $photo.add($date).add($message).on("change input", composeComment);
}

// Кнопка «наверх»
function initBtnUp() {
    const $btnUp = $(".btn-up");

    if (!$btnUp.length) {
        return;
    }

    $(window).on("scroll", function () {
        $btnUp.toggleClass("btn-up_hide", $(window).scrollTop() <= 400);
    });

    $btnUp.on("click", function () {
        $("html, body").animate({scrollTop: 0}, 400);
    });
}

$(document).ready(function () {
    initGallery();
    initBookingForm();
    initBtnUp();
});
