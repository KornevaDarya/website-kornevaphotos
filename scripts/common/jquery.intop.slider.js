import {arcticmodal_settings} from "../functions/modal";

var effect = 0, sliderImages, slideRight, slideLeft, imgCount = 0, arrSrc = [], isHoveredRight = true;

export function sliderInit(containerImg, arrSrcPre) {
    var container = containerImg;
    // Если параметр не задан, то все картинки находятся в одной группе
    if (containerImg == undefined) {
        containerImg = $("img");
    }

    // Если текущий элемент не является картинкой, то искать картинки внутри него
    if (!containerImg.is("img") && (containerImg.attr("data-imgSrc") == undefined) && (containerImg.attr("data-imgSrcArr") == undefined)) {
        containerImg = containerImg.find("img");
    }

    // Устанавливаем нужный курсор для выбранных картинок
    containerImg.css("cursor", "pointer");

    container.click(function () {
        var thisIndex = containerImg.index(this);
        if (thisIndex == -1) {
            thisIndex = 0;
        }

        // Получение и сортировка картинок
        if ((arrSrcPre != undefined) && (arrSrcPre != null) && (Object.prototype.toString.call(arrSrcPre) === "[object Array]")) {
            arrSrc = arrSrcPre;
        } else if (containerImg.attr("data-imgSrcArr") != undefined) {
            arrSrc = containerImg.attr("data-imgSrcArr").split(", ");
        } else {
            arrSrc = containerImg.map(function () {
                return ($(this).attr("data-imgSrc") != undefined) ? $(this).attr("data-imgSrc").split("m.").join(".") : $(this).attr("src").split("m.").join(".");
            }).get();
        }
        arrSrc = (arrSrc.slice(thisIndex)).concat(arrSrc.slice(0, thisIndex));
        imgCount = arrSrc.length;

        // Eсли в группе всего одна картинка, то прячем стрелки
        if (imgCount == 1) {
            slideLeft.hide();
            slideRight.hide()
        } else {
            slideLeft.show();
            slideRight.show()
        }

        // Предварительно очищаем контейнер со слайдами
        sliderImages.width("99999").html("");	// из-за этой ширины слайдер не центрируется в IE7

        // Добавляем нужное количество картинок в контейнер
        for (var i = 0; i < imgCount; i++) {
            $("<img>").addClass("intopModal__sliderImgLeft").appendTo(sliderImages);
        }

        // Подгрузка картинок
        var sliderImagesImg = sliderImages.find("img");
        sliderImagesImg.eq(0).attr("src", arrSrc[0]);
        sliderImagesImg.eq(1).attr("src", arrSrc[1]);
        sliderImagesImg.eq(-1).attr("src", arrSrc[imgCount - 1]);

        checkSize(sliderImagesImg.first());
        $(".intopModal-slider").arcticmodal(arcticmodal_settings);
    });
}

function checkSize(img) {
    var maxHeight = (parseFloat(window.innerHeight) || parseFloat($(window).height())) - parseFloat($(".arcticmodal-container_i2").css("padding-top")) - parseFloat($(".arcticmodal-container_i2").css("padding-bottom")),
        maxWidth = (parseFloat(window.innerWidth) || parseFloat($(window).width())) - parseFloat($(".arcticmodal-container_i2").css("padding-left")) - parseFloat($(".arcticmodal-container_i2").css("padding-right"));

    img.width("auto").height("auto");

    // Получаем размер картинки
    if (img.height() > 30) {
        var nWidth = img.width(), nHeight = img.height(), k = 0.0;

        if (nWidth > maxWidth) {
            k = maxWidth / nWidth;
            nWidth = parseInt(nWidth * k);
            nHeight = parseInt(nHeight * k);
        }
        if (nHeight > maxHeight) {
            k = maxHeight / nHeight;
            nWidth = parseInt(nWidth * k);
            nHeight = parseInt(nHeight * k);
        }

        img.width(nWidth).height(nHeight);
        $(".intopModal-slider").width(nWidth).height(nHeight);
    } else {
        setTimeout(function () {
            checkSize(img)
        }, 2);
    }
}

function moveRight_0() {
    var img = sliderImages.find("img").first(), nextImg = img.next();

    // Подгружаем следующее изображение
    if (nextImg.attr("src") == undefined) {
        nextImg.attr("src", arrSrc[1]);
    }

    // Подгружаем еще парочку изображений
    if (nextImg.next().attr("src") == undefined) {
        nextImg.next().attr("src", arrSrc[2]);
    }
    if (nextImg.next().next().attr("src") == undefined) {
        nextImg.next().next().attr("src", arrSrc[3]);
    }

    checkSize(nextImg);
    img.appendTo(sliderImages);

    // Сортируем массив
    arrSrc.push(arrSrc.shift());
}

function moveLeft_0() {
    var img = sliderImages.find("img").first(), nextImg = sliderImages.find("img").last();

    // Подгружаем следующее изображение
    if (nextImg.attr("src") == undefined) {
        nextImg.attr("src", arrSrc[imgCount - 1]);
    }

    // Подгружаем еще парочку изображений
    if (nextImg.prev().attr("src") == undefined) {
        nextImg.prev().attr("src", arrSrc[imgCount - 2]);
    }
    if (nextImg.prev().prev().attr("src") == undefined) {
        nextImg.prev().prev().attr("src", arrSrc[imgCount - 3]);
    }

    checkSize(nextImg);
    nextImg.prependTo(sliderImages);

    // Сортируем массив
    arrSrc.unshift(arrSrc.pop());
}

if (!$(".intopModal-slider").length) {
    $(document).ready(function () {
        var myModal = '<div class="intopModal intopModal-slider">' + '<button class="intopModal__close arcticmodal-close" type="button">\n' +
            '        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">\n' +
            '            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.41425 7.00025L13.7072 1.70725C14.0982 1.31625 14.0982 0.68425 13.7072 0.29325C13.3162 -0.09775 12.6842 -0.09775 12.2933 0.29325L7.00025 5.58625L1.70725 0.29325C1.31625 -0.09775 0.68425 -0.09775 0.29325 0.29325C-0.09775 0.68425 -0.09775 1.31625 0.29325 1.70725L5.58625 7.00025L0.29325 12.2933C-0.09775 12.6842 -0.09775 13.3162 0.29325 13.7072C0.48825 13.9022 0.74425 14.0002 1.00025 14.0002C1.25625 14.0002 1.51225 13.9022 1.70725 13.7072L7.00025 8.41425L12.2933 13.7072C12.4882 13.9022 12.7443 14.0002 13.0002 14.0002C13.2562 14.0002 13.5122 13.9022 13.7072 13.7072C14.0982 13.3162 14.0982 12.6842 13.7072 12.2933L8.41425 7.00025Z"/>\n' +
            '        </svg>\n' +
            '    </button>' + '<div class="intopModal__sliderBox">' + '<div class="intopModal__sliderImages"></div>' + '</div>' + '<div class="intopModal__sliderBtnLeft"><svg class="icon svg-sprite-icon icon-prev">\n' +
            '    <use xlink:href="#icon__prev"></use>\n' +
            '</svg></div>' + '<div class="intopModal__sliderBtnRight"><svg class="icon svg-sprite-icon icon-next">\n' +
            '    <use xlink:href="#icon__next"></use>\n' +
            '</svg></div>' + '</div>';
        $(myModal).appendTo(".intopModal__wrap");

        // Значения глобальных переменных
        sliderImages = $(".intopModal__sliderImages");
        slideRight = $(".intopModal__sliderBtnRight");
        slideLeft = $(".intopModal__sliderBtnLeft");

        // Обновление размеров слайдера при изменении размера окна
        $(window).resize(function () {
            checkSize(sliderImages.find("img").first())
        });

        // Листание слайдов с клавиатуры - стрелочки, пробел
        document.onkeydown = function checkKeydown(event) {
            var keycode;
            if (!event) {
                var event = window.event;
            }
            if (event.keyCode) {
                keycode = event.keyCode;
            }// IE
            else if (event.which) {
                keycode = event.which;
            } // all browsers
            if (keycode == 39) {
                moveRight_0();
            } else if (keycode == 37) {
                moveLeft_0();
            } else if (keycode == 32) {
                moveRight_0();
            }
        }

        // Клик по стрелкам слайдера
        if (effect == 0) {
            slideRight.click(function () {
                moveRight_0();
            });
            slideLeft.click(function () {
                moveLeft_0();
            });
        }

        // Клик по картинке. Этот код избавляет нас от кнопки "увеличить".
        $(".intopModal__sliderImages").on("mousemove", (function (event) {
            if (isHoveredRight) {
                if (event.pageX < window.innerWidth / 2) {
                    isHoveredRight = false;
                    slideRight.removeClass("hovered");
                    slideLeft.addClass("hovered");
                }
            } else {
                if (event.pageX > window.innerWidth / 2) {
                    isHoveredRight = true;
                    slideLeft.removeClass("hovered");
                    slideRight.addClass("hovered");
                }
            }
        }))
            .on("mouseup", (function (event) {
                /* переключаем только по нажатию левой кнопки мыши */
                if (event.which === 1) {
                    if (event.pageX > window.innerWidth / 2) {
                        moveRight_0();
                    } else {
                        moveLeft_0();
                    }
                }
            }));

        // Mobile swipe (сдвиг влево / вправо на мобильных)
        var dragStartX, dragDiff, dragGap = 10, touch;
        $(".intopModal-slider").on("touchstart", (function (event) {
            touch = event.originalEvent.touches[0] || event.originalEvent.changedTouches[0];
            dragStartX = touch.pageX;

        })).on("touchend", (function (event) {
            touch = event.originalEvent.touches[0] || event.originalEvent.changedTouches[0];
            dragDiff = dragStartX - touch.pageX;

            if (Math.abs(dragDiff) > dragGap) {
                if (dragDiff > 0) {
                    moveRight_0();
                } else {
                    moveLeft_0();
                }
            } else {

            }
        }));

    });
}