{extends "page.tpl"}

{block main}
    {include "blocks/page-error.tpl" page_error=[
        "code" => "415",
        "title" => "Ошибка 415",
        "text" => "Данный тип запроса не поддерживается"
    ]}
{/block}
