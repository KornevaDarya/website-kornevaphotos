{extends "page.tpl"}

{block main}
    {include "blocks/page-error.tpl" page_error=[
        "code" => "404",
        "title" => "Ошибка 404 Страница не найдена",
        "text" => "Извините, страница не найдена"
    ]}
{/block}
