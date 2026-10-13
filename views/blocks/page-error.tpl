<section class="page-error">
    <div class="container page-error__container">
        <h2 class="page-error__title">{$page_error.title}</h2>
        <p class="page-error__text">{$page_error.text}</p>
        <a class="btn btn-default btn-green-white-green page-error__btn" href="/">Вернуться на главную</a>
        <picture class="page-error__picture">
            <source srcset="/img/og-image.webp" type="image/webp">
            <img class="page-error__image image"
                 src="/img/og-image.jpg"
                 alt="{$page->seo->short}"
                 loading="lazy">
        </picture>
    </div>
</section>
