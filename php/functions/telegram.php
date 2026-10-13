<?
    /**
     * Отправка уведомлений в Telegram.
     * Токены и chat_id берутся из .env, при пустых значениях отправка пропускается.
     */

    /**
     * Отправляет сообщение в чат через Telegram Bot API
     * @param string $api_key
     * @param string $chat_id
     * @param string $text уже urlencode-нутый текст
     * @return bool|string
     */
    function send_telegram_message(string $api_key, string $chat_id, string $text)
    {
        if ($api_key === "" || $chat_id === "") {
            return false;
        }

        $url = "https://api.telegram.org/bot$api_key/sendMessage?chat_id=$chat_id&parse_mode=HTML&text=$text";
        $ch = curl_init();
        curl_setopt_array($ch, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true]);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return $server_output;
    }

    /**
     * Защита от дублей: возвращает true, если сообщение с таким хешем ещё не отправлялось
     * @param string $md5
     * @param int $max_age время жизни блокировки в секундах
     * @return bool
     */
    function telegram_can_send(string $md5, int $max_age): bool
    {
        $dir = ROOT . "/cache/telegram_md5";

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file_name = "$dir/$md5.log";

        if (file_exists($file_name) && ((time() - filemtime($file_name)) <= $max_age)) {
            return false;
        }

        file_put_contents($file_name, "-");

        return true;
    }

    /**
     * Отправка ошибки в Telegram
     * @param array $data
     * @return bool|string
     */
    function send_telegram_error(array $data)
    {
        $api_key = $_ENV["TELEGRAM_ERROR_KEY"] ?? "";
        $chat_id = $_ENV["TELEGRAM_ERROR_CHAT_ID"] ?? "";

        if ($api_key === "" || $chat_id === "") {
            return false;
        }

        if (!telegram_can_send(md5(json_encode($data)), 10)) {
            return false;
        }

        $txt = "";
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $txt .= "<b>" . urlencode(trim($key, "\: \t\n\r\0\x0B")) . "</b>:" . urlencode(" " . $value) . "%0A";
            }
        }

        return send_telegram_message($api_key, $chat_id, $txt);
    }

    /**
     * Отправка заявки в Telegram
     * @param bool $is_success
     * @param string|null $phone
     * @param string|null $roistat
     * @param string|null $ym_uid
     * @param string|null $comment
     * @return bool|string
     */
    function send_telegram_request(bool $is_success, ?string $phone, ?string $roistat, ?string $ym_uid, ?string $comment = null)
    {
        $api_key = $_ENV["TELEGRAM_REQUEST_KEY"] ?? "";
        $chat_id = $_ENV["TELEGRAM_REQUEST_CHAT_ID"] ?? "";

        if ($api_key === "" || $chat_id === "") {
            return false;
        }

        if (!telegram_can_send(md5($phone ?: generate_random_string()), 5 * 60)) {
            return false;
        }

        $arr = [];
        if (!empty($phone)) {
            $arr["Телефон"] = numbers_from_str($phone);
        }
        if (!empty($roistat)) {
            $arr["Roistat"] = $roistat;
        }
        if (!empty($ym_uid)) {
            $arr["ym_uid"] = $ym_uid;
        }
        if (!empty($comment)) {
            $arr["Комментарий"] = $comment;
        }

        if ($is_success) {
            $txt = urlencode("Успешная заявка") . "%0A";
        } else {
            $txt = "<b>" . urlencode("СПАМ НЕ ОТПРАВЛЕН") . "</b>%0A";
        }

        foreach ($arr as $key => $value) {
            if (!empty(trim($value))) {
                $txt .= urlencode(trim($key, "\: \t\n\r\0\x0B")) . ":" . urlencode(" " . $value) . "%0A";
            }
        }

        return send_telegram_message($api_key, $chat_id, $txt);
    }
