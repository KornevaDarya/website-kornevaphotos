import {arcticmodal_settings, openModal} from "../functions/modal";
import {localStorageOnePerDay} from "../functions/storage";
import {scrollToBlock} from "../functions/scroll";
import {sliderInit} from "./jquery.intop.slider";

$(document).ready(function () {
    $(".link-to").click(function (event) {
        event.preventDefault();
        scrollToBlock($(this).data("scroll"));
    });
});

$.ajaxSetup({cache: false});

$.fn.padding = function (direction) {
    return parseFloat(this.css("padding-" + direction));
};

$("input[type='tel']").mask("+7(999)999-99-99", {placeholder: "+7(___) --- -- --"}).on("click", function () {
    $(this).focus();
});

$(".js-fancybox").fancybox();

function initZoomImg() {
    $(".g-zoomImgSlider").each(function () {
        sliderInit($(this).find(".g-zoomImg").find("img"));
    });

    $(".g-zoomImgSingle").each(function () {
        sliderInit($(this));
    })
}

initZoomImg();