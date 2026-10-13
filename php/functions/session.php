<?
    const
        STORED_VALUE_STRING = "string",
        STORED_VALUE_INT = "int",
        STORED_VALUE_FLOAT = "float",
        STORED_VALUE_ARRAY = "array",
        STORED_VALUE_OBJECT = "object",

    CACHE_INDEX_CART = 2,
    CACHE_INDEX_MSG = 2,
    CACHE_INDEX_SESSION = 2;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        $session_name = session_name("session_" . CACHE_INDEX_SESSION);
        session_set_cookie_params(1209600, "/", $_ENV["COOKIE_HOST"] ?? "");
        session_start();
    }

    /**
     * @param string $name
     * @param string $value_type
     * @param string $object_name
     * @return float|int|mixed|null
     * @throws JsonMapper_Exception
     */
    function get_stored_value(string $name, string $value_type, string $object_name = ""): mixed
    {
        if (!empty($_SESSION[$name])) {
            $stored_value = $_SESSION[$name];
        } elseif (!empty($_COOKIE[$name])) {
            $stored_value = $_COOKIE[$name];
        } else {
            return null;
        }

        switch ($value_type) {
            case STORED_VALUE_INT:
                return intval($stored_value);
            case STORED_VALUE_FLOAT:
                return floatval($stored_value);
            case STORED_VALUE_ARRAY:
                return json_decode($stored_value, true);
            case STORED_VALUE_OBJECT:
                $object = new $object_name();
                $mapper = new JsonMapper();
                $mapper->bIgnoreVisibility = true;
                $mapper->map(json_decode($stored_value), $object);
                return $object;
            default:
                return $stored_value;
        }
    }

    /**
     * @param string $name
     * @param $value
     * @param string $value_type
     * @return void
     */
    function set_stored_value(string $name, $value, string $value_type): void
    {
        if (in_array($value_type, [STORED_VALUE_ARRAY, STORED_VALUE_OBJECT])) {
            $stored_value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $stored_value = $value;
        }

        $_SESSION[$name] = $stored_value;
        setcookie($name, $stored_value, time() + 1209600, "/", $_ENV["COOKIE_HOST"] ?? "");
    }

    /*function clean_old_values() {
        for ($i = 1; $i < CACHE_INDEX_CART; $i++) {
            $old_index = "cart_$i";
            if (!empty($_SESSION[$old_index]) || !empty($_COOKIE[$old_index])) {
                clean_stored_value($old_index);
            }
        }

        for ($i = 1; $i < 16; $i++) {
            $old_index = "restaurant_$i";
            if (!empty($_SESSION[$old_index]) || !empty($_COOKIE[$old_index])) {
                clean_stored_value($old_index);
            }
        }

        for ($i = 1; $i < CACHE_INDEX_MSG; $i++) {
            $old_index = "messages_$i";
            if (!empty($_SESSION[$old_index]) || !empty($_COOKIE[$old_index])) {
                clean_stored_value($old_index);
            }
        }
    }

    clean_old_values();*/

    /**
     * @param string $name
     * @return void
     */
    function clean_stored_value(string $name): void
    {
        unset($_SESSION[$name]);
        unset($_COOKIE[$name]);
        setcookie($name, "", -1, "/", $_ENV["COOKIE_HOST"] ?? "");
    }

    /**
     * @return void
     */
    function clear_cache(): void
    {
        global $memcached;
        if ($memcached) {
            $memcached->flush();
        }
        $folder_paths = [ROOT . "/cache/templates"];
        foreach ($folder_paths as $folder_path) {
            $files = glob($folder_path . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }