import {windowHeight, windowWidth} from "./size";

export let arcticmodal_settings = {
    overlay: {
        css: {opacity: .5},
    },
    beforeOpen(data, modal) {
        let modalParent = $(modal).parent();
        let maxHg =
            windowHeight() -
            modalParent.padding("top") -
            modalParent.padding("bottom");
        $(modal).css("max-height", maxHg);

        let bodyPage = $(".page").outerWidth(true);
        let scrollWidth = windowWidth() - bodyPage;

        if (scrollWidth > 0 && bodyPage <= 1920) {
            $(".lock-padding").css({"padding-right": scrollWidth});
        }
    },
    afterClose(data, modal) {
        $(".lock-padding").css({"padding-right": 0});
        $(".intopModal__content input").removeClass("input-error");
        $(".request__input-comment_wrap").empty();
    }
};

/**
 * Функция открывает попап СПАСИБО
 */
export function openModalThanks() {
    let $modal = $(".intopModal-thanks");
    if ($modal.length > 0) {
        if ($(".page__body").hasClass("arcticmodal-wrap-active")) {
            $(".arcticmodal-container .intopModal").arcticmodal("close");
            setTimeout(() => {
                $modal.arcticmodal(arcticmodal_settings);
            }, 100);
        } else {
            $modal.arcticmodal(arcticmodal_settings);
        }
    }
}

export function openModal(eng, arcticmodal_setting_modal) {
    let $modal = $(".intopModal-" + eng);
    if ($modal.length > 0) {
        if ($(".page__body").hasClass("arcticmodal-wrap-active")) {
            $(".arcticmodal-container .intopModal").arcticmodal("close");
            setTimeout(() => {
                $modal.arcticmodal(arcticmodal_setting_modal);
            }, 100);
        } else {
            $modal.arcticmodal(arcticmodal_setting_modal);
        }
    }
}

/**
 * Функция отрисовывает фото в модалке просмотра (view)
 * @param {string} imgSrc путь к картинке
 */
export function renderViewModalImage(imgSrc) {

    $(".intopModal-view").attr("data-view", "image");
    $(".intopModal-view .m-view__wrap").remove();

    // <source srcset="${imgSrc}.webp" type="image/webp">
    var picture = `<picture class="m-view__wrap">
                        <img class="m-view__image"
                            src="${imgSrc}.jpg"
                            alt="Изображение"
                            width="1800"
                            height="900"
                            loading="lazy">
                    </picture>`;
    $(".intopModal-view .m-view").append(picture);
}

/**
 * Функция отрисовывает видео в модалке просмотра (view)
 * @param {string} videoSrc путь к видео
 */
export function renderViewModalVideo(videoSrc) {
    $(".intopModal-view").attr("data-view", "video");
    $(".intopModal-view .m-view__wrap").remove();

    var video = `<div class="m-view__wrap">
                        <video class="video-track m-view__video" controls autoplay playsinline >
                            <source src="${videoSrc}.webm" type="video/webm">
                            <source src="${videoSrc}.mp4" type="video/mp4">
                            Your browser doesn't support HTML5 video tag.
                        </video>
                    </div>`
    $(".intopModal-view .m-view").append(video);
}

/**
 * Открытие модалки просмотра (view)
 */
export function openModalView() {
    let $modal = $(".intopModal-view");
    if ($modal.length > 0) {
        if ($(".page__body").hasClass("arcticmodal-wrap-active")) {
            $((".arcticmodal-container .intopModal")).arcticmodal('close');
            setTimeout(() => {
                $modal.arcticmodal(arcticmodal_settings);
            }, 100);
        } else {
            $modal.arcticmodal(arcticmodal_settings);
        }
    }
}
