<?
    require_once __DIR__ . "/../sms/sms.ru.php";

    /**
     * Отправка SMS через sms.ru (SDK)
     *
     * @param string $phone — телефон в любом виде, нормализуется до 11 цифр
     * @param string $text  — текст сообщения в UTF-8
     * @return bool — true если sms.ru подтвердил приём; false при сетевой/валидационной ошибке
     */
    function send_sms(string $phone, string $text): bool
    {
        $api_id = $_ENV["SMSRU_API_ID"] ?? "";
        if ($api_id === "") {
            return false;
        }

        $phone_normalized = normalize_phone($phone);
        if ($phone_normalized === "") {
            return false;
        }

        $smsru = new SMSRU($api_id);
        $data = new stdClass();
        $data->to = $phone_normalized;
        $data->msg = $text;

        $sms = $smsru->send_one($data);
        return is_object($sms) && (($sms->status ?? "") === "OK");
    }
