import {arcticmodal_settings} from "../functions/modal";
import {$body} from "../functions/variables";
import {localStorageOnePerDay} from "../functions/storage";

function openModalThanks(phone, msg) {
    msg = msg || `Заявка отправлена.<br><br>Наш менеджер свяжется с Вами очень скоро.<br><br>Убедитесь, что указали верный номер ${phone}`;
    $.arcticmodal("close");
    alert(msg);
}

$body.on("click", ".js-request", function () {
    $(".modal__request-title").text($(this).data("h")?.trim() || "Отправить заявку");
    $(".intopModal__content").find(".request__input-from").val($(this).data("from")?.trim() || `Кнопка: ${$(this).text()?.trim()}`);
    $(".request__submit").find(".btn__text").text($(this).data("btn")?.trim() || "Отправить заявку");
    var modalReq = $(this).data("modal-type") ? $(this).data("modal-type") : "callback";

    if ($(this).data("out-container")) {
        let comment = '';
        let $additionalInputs = $("." + $(this).data("out-container")).find(".js-input-additional");

        $additionalInputs.each(function () {
            let label = $(this).data("label");
            let val = $(this).val();
            comment += label + ': ' + val + '; ';
        });

        $(".request__input-comment").val(comment);
    }

    $(".intopModal-" + modalReq).arcticmodal(arcticmodal_settings);
});


$(".request__form").submit(function (event) {
    event.preventDefault();

    var
        $form = $(this),
        $modal = $(this).parents(".section, .intopModal"),
        from = $form.find(".request__input-from").val(),
        comment = $form.find(".request__input-comment").val();

    let roistat = "";
    if (window.roistat && window.roistat.visit) {
        roistat = window.roistat.visit;
    }

    let data = {
        key: ajaxKey,
        ymUid: getCookie("_ym_uid"),
        name: $form.find(".request__input-name").val() ? $form.find(".request__input-name").val().trim() : "Нет данных",
        phone: $form.find(".request__input-phone").val().replace(/\D/g, ""),
        from: from,
        comment: comment,
        roistat: roistat
    }

    if ($(".request__checkbox").prop("checked")) {
        if (data.phone) {
            $modal.find(".request-loader").show();

            $.ajax({
                type: "POST",
                url: "/php/callme2.php",
                data: data,
                dataType: "json",
                success: function (result) {
                    if (result["error"]) {
                        alert(result["error"]);
                    } else {
                        // обнуляем форму
                        $form[0].reset();
                        // открываем попап СПАСИБО
                        openModalThanks(data.phone);
                    }
                },
                complete: function () {
                    // id счётчика приходит из counters.tpl (значение YM_COUNTER из .env)
                    if (window.ym && window.ymCounter) {
                        localStorageOnePerDay("request_sent", function () {
                            ym(window.ymCounter, "reachGoal", "REQUEST_SENT")
                        });
                    }
                    $modal.find(".request-loader").hide();
                }
            });
        } else {
            alert("Пожалуйста, укажите Ваш номер телефона, чтобы мы смогли позвонить Вам!");
        }
    } else {
        alert("Пожалуйста, дайте согласие на обработку ваших персональных данных.");
    }
});