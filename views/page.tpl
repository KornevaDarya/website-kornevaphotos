<!DOCTYPE html>
<html class="page page_{$page->eng}" lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1">

        {* Title, description, etc *}
        {include "blocks/head/seo.tpl"}

        {* Загрузка шрифтов *}
        {include "blocks/head/loadfonts.tpl"}

        {* Фавиконки *}
        {include "blocks/head/favicons.tpl"}

        {* Микроразметка Open Graph *}
        {include "blocks/head/open-graph.tpl"}

        {* Микроразметка Schema.org *}
        {include "blocks/head/schema.tpl"}

        {* Стили, общие для всех страниц *}
        {$common_styles}

        {* Стили текущей страницы *}
        {if !empty($page->styles)}
            {$page->styles}
        {/if}

        {block inline_styles}{/block}

        {* Критические скрипты *}
        {$inline_scripts}

        <script>
            var ajaxKey = "{get_secret_key()}";
        </script>
    </head>
    <body class="page__body page__body_{$page->eng}">
        <script>checkWebpSupport();</script>
        {file_content("/img/sprite.svg")}

        {* Основной заголовок страницы *}
        <h1 class="page__h1 visually-hidden">{$page->seo->short}</h1>
        {include "blocks/header.tpl" class= "page__header page__header_{$page->eng}"}

        {* Основной контент страницы *}
        <main class="{$page->eng} page__main page__main_{$page->eng}">
            {block main}{/block}
        </main>

        {* Футер страницы *}
        {include "blocks/footer.tpl" footer=[
            "class" => "page__footer page__footer_{$page->eng}"
        ]}

        {* Вендорные скрипты *}
        {$vendor_scripts}

        {* Скрипты, общие для всех страниц *}
        {$common_scripts}

        {* Скрипты текущей страницы *}
        {if !empty($page->scripts)}
            {$page->scripts}
        {/if}
    </body>
</html>
