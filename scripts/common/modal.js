import {arcticmodal_settings, openModal} from "../functions/modal";
import {$body} from "../functions/variables";
import { renderViewModalImage, openModalView } from "../functions/modal";

$(document).ready(function () {
    window.alert = function (content) {
        $(".intopModal-alert .alert__content").html(content).parents(".intopModal-alert").arcticmodal(arcticmodal_settings);
    }
    $(window).resize(function () {
        arcticmodal_settings.beforeOpen(null, ".intopModal:visible");
    });

    $body.on("click", "[data-modal]", function (event) {
        event.preventDefault();
        let h = $(this).data("h")
        $(".intopModal-callback").find(".modal__request-title").html(h)
        let btnText = $(this).data("btn")
        $(".intopModal-callback").find(".request-btn__txt").html(btnText)
        let isComment = $(this).data("comment")
        if (isComment == "yes") {
            $(".intopModal-callback").find(".request__input-comment_wrap").html(`<div class="request__input_wrap">
                                <label class="request__field request__field-comment">
                                    <input class="input-default request__input request__input-comment" type="text" placeholder="Текст вопроса">
                                </label>
                            </div>`)
        }
        openModal($(this).data("modal"), arcticmodal_settings);
    });
});


//============ модальное окно просмотра
$(".viewImg").click(function () {
    var imgSrc = $(this).attr("src");
    if (!imgSrc) {
      imgSrc = $(this).data("src");
    }
    // отрисовать фото в модалке просмотра и открыть модалку
    renderViewModalImage(imgSrc.split(".")[0]);
    openModalView();
  });





