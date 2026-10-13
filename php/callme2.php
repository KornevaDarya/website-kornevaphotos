<?
    const ROOT = __DIR__ . "/..";

    include ROOT . "/php/hide-errors.php";
    include ROOT . "/vendor/autoload.php";
    include ROOT . "/php/common.php";

    const EMAIL_SUBJECT_PREFIX = "Заявка с сайта";

    header("Content-Type: application/json; charset=utf-8");

    function respond(bool $success, string $error = ""): void
    {
        echo json_encode(["success" => $success, "error" => $error], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (empty($_POST["key"]) || $_POST["key"] !== get_secret_key()) {
        sleep(rand(1, 3));
        respond(true);
    }

    $name = trim((string)($_POST["name"] ?? ""));
    $phone_raw = (string)($_POST["phone"] ?? "");
    $phone = numbers_from_str($phone_raw);

    if ($name === "" || $phone === "") {
        respond(false, "Заполните имя и телефон");
    }
    if (mb_strlen($name) > 255) {
        $name = mb_substr($name, 0, 255);
    }
    if (mb_strlen($phone) > 255) {
        $phone = mb_substr($phone, 0, 255);
    }

    // 1) SMS клиенту — fire-and-forget, ответ sms.ru игнорируем
    send_sms($phone, "Ваша заявка успешно отправлена. Менеджер свяжется с вами в ближайшее время.");

    $sitename = site_name();
    $mailing      = (isset($_POST["mailing"]) && $_POST["mailing"] === "согласен") ? "согласен" : "не согласен";
    $policy_agree = (isset($_POST["policy_agree"]) && $_POST["policy_agree"] === "согласен") ? "согласен" : "не согласен";
    $referer = new Referer();

    // 2) Письмо на почту
    $email_to = array_filter(array_map("trim", explode(",", env_str("EMAIL_TO"))));
    $email_from = env_str("EMAIL_FROM");

    if ($email_to && $email_from) {
        $smarty = init_smarty();
        $smarty->assign([
            "name" => $name,
            "phone" => $phone,
            "site" => $sitename,
            "date" => date("d.m.Y H:i"),
        ]);
        $message = $smarty->fetch(ROOT . "/views/letters/request.tpl");

        $subject = "=?utf-8?B?" . base64_encode(EMAIL_SUBJECT_PREFIX . " $sitename") . "?=";
        $headers = "From: =?utf-8?B?" . base64_encode($sitename) . "?= <" . $email_from . ">\r\n"
            . "Content-Type: text/html; charset=utf-8\r\n";

        foreach ($email_to as $to) {
            @mail($to, $subject, $message, $headers);
        }
    }

    $roistat = $_POST["roistat"] ?? "";
    if ($roistat === "" && !empty($_COOKIE["roistat_visit"])) {
        $roistat = $_COOKIE["roistat_visit"];
    }

    $ym_uid = $_POST["ymUid"] ?? "";
    if ($ym_uid === "" && !empty($_COOKIE["_ym_uid"])) {
        $ym_uid = $_COOKIE["_ym_uid"];
    }

    $assigned_by_id = numbers_from_str((string)($_POST["assigned_by_id"] ?? ""));
    $manager_name = trim((string)($_POST["manager"] ?? ""));

    // 3) Отправка в CRM
    $crm_hook_url = env_str("CRM_HOOK_URL");

    if ($crm_hook_url !== "") {
        $crm_payload = [
            "sitename"       => $sitename,
            "phone"          => $phone,
            "name"           => $name,
            "email"          => $_POST["email"] ?? "",
            "comment"        => $_POST["comment"] ?? "",
            "from"           => $_POST["from"] ?? "",
            "roistat"        => $roistat,
            "ym_uid"         => $ym_uid,
            "ym_counter"     => env_str("YM_COUNTER"),
            "zhk"            => env_str("CRM_ZHK"),
            "manager"        => $manager_name,
            "assigned_by_id" => $assigned_by_id,
            "utm_source"     => $referer->utm_original->utm_source ?? "",
            "utm_campaign"   => $referer->utm_original->utm_campaign ?? "",
            "utm_medium"     => $referer->utm_original->utm_medium ?? "",
            "utm_content"    => $referer->utm_original->utm_content ?? "",
            "utm_term"       => $referer->utm_original->utm_term ?? "",
            "referrer"       => $_POST["referrer"] ?? ($_SERVER["HTTP_REFERER"] ?? ""),
            "mailing"        => $mailing,
            "policy_agree"   => $policy_agree
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $crm_hook_url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($crm_payload),
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT        => 90,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    // 4) Уведомление в Telegram
    send_telegram_request(true, $phone, $roistat, $ym_uid, $_POST["comment"] ?? null);

    respond(true);
