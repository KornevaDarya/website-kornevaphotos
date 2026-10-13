<?
    /**
     * Соль для подписи ajax-запросов. Задаётся в .env
     * @return string
     */
    function anti_spam_key(): string
    {
        return (string)($_ENV["ANTI_SPAM_KEY"] ?? "");
    }

    /**
     * Получаем секретный ключ для передачи ajax запросов
     * @return string
     */
    function get_api_url_additional_hash(): string
    {
        $result = date("Y-m-d", strtotime("-4 hours"));
        if (env_mode() == ENV_MODE_PROD) {
            //$ip = get_user_ip();
            //if (!empty($ip))
            //    $result .= md5($ip);
            if (isset($_SERVER["HTTP_HOST"]))
                $result .= md5($_SERVER["HTTP_HOST"]);
            if (isset($_SERVER["HTTP_USER_AGENT"]))
                $result .= md5($_SERVER["HTTP_USER_AGENT"]);
        }
        return sha1(base64_encode($result) . anti_spam_key());
    }

    /**
     * @return bool
     */
    function is_server_run(): bool
    {
        global $argv;

        if (!empty($argv)) {
            if (in_array("cli", $argv)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $pass
     * @return bool
     */
    function user_auth(string $login, string $pass): bool
    {
        $query_user = "SELECT * FROM AT_USER au WHERE (au.AT_USER_LOGIN = '" . db_real_escape_string($login) . "') AND (au.AT_USER_PASSWORD = AES_ENCRYPT('" . db_real_escape_string($pass) . "','$_ENV[AESKEY]'))";
        $res_user = db_query($query_user);
        if ($res_user && db_num_rows($res_user)) {
            $row_user = db_fetch_assoc($res_user);
            $_SESSION["ID_AT_USER"] = $row_user["ID_AT_USER"];
            $_SESSION["AT_USER_LOGIN"] = $row_user["AT_USER_LOGIN"];
            $_SESSION["AT_USER_SHORT"] = $row_user["AT_USER_SHORT"];
            $_SESSION["AT_USER_PHONE"] = $row_user["AT_USER_PHONE"];
            $_SESSION["ID_AT_USER_PERMISSION"] = $row_user["ID_AT_USER_PERMISSION"];
            $_SESSION["AT_USER_IS_ACTIVE"] = $row_user["AT_USER_IS_ACTIVE"];

            return true;
        } else {
            return false;
        }
    }

    /**
     * Выход пользователя
     * @return void
     */
    function user_logout(): void
    {
        unset($_SESSION["ID_AT_USER"]);
        unset($_SESSION["AT_USER_LOGIN"]);
        unset($_SESSION["AT_USER_SHORT"]);
        unset($_SESSION["AT_USER_PHONE"]);
        unset($_SESSION["ID_AT_USER_PERMISSION"]);
        unset($_SESSION["AT_USER_IS_ACTIVE"]);
    }


    /**
     * Скрипт очистки папок кеша
     * @param string $cache_folder
     * @return void
     */
    function clean_cache_full(string $cache_folder): void
    {
        $folders_to_delete = [ROOT . "/cache/$cache_folder/*/*/*.*", ROOT . "/cache/$cache_folder/*/*.*", ROOT . "/cache/$cache_folder/*.*", ROOT . "/cache/$cache_folder/*/*", ROOT . "/cache/$cache_folder/*"];

        foreach ($folders_to_delete as $glob_path) {
            foreach (glob($glob_path) as $file) {
                if (is_file($file)) {
                    unlink($file);
                } elseif (is_dir($file)) {
                    rmdir($file);
                }
            }
        }
    }