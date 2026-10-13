<?
    const ROOT = __DIR__;

    include ROOT . "/php/hide-errors.php";
    include ROOT . "/vendor/autoload.php";
    include ROOT . "/php/common.php";

    $page = Page::get_page();
    $referer = new Referer();

    if ($page->code === 404) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }

    //инициализируем шаблонизатор
    $smarty = init_smarty();

    $smarty->assign([
        "page" => $page,
        "fact_url" => new Url(),
        "phone" => new Phone(SITE_PHONE),
        "email" => SITE_EMAIL,
        "address1" => SITE_ADDRESS1,
        "address2" => SITE_ADDRESS2,
        "products" => json_data("products"),
        "product_categories" => json_data("product-categories"),
        "ym_counter" => env_str("YM_COUNTER"),
        "roistat_id" => env_str("ROISTAT_ID"),
        "inline_scripts" => external_scripts(actual_bundle_path("dist/js", "inline")),
        "vendor_scripts" => external_scripts(actual_bundle_path("dist/js", "vendor")),
        "common_scripts" => external_scripts(actual_bundle_path("dist/js", "common")),
        "vendor_styles" => external_styles(actual_bundle_path("dist/css/prod", "vendor")),
        "common_styles" => styles_by_mode("common"),
    ])->display($page->view);
