<?

    /**
     * Логирование с созданием папок год-месяц-день
     * @param string $log_path
     * @param $log_data
     * @param string $log_name
     * @param string $date_ymh_his
     * @return void
     */
    function log_global(string $log_path, $log_data, string $log_name, string $date_ymh_his = "") {
        if (empty($date_ymh_his)) {
            $date_ymh_his = date("Ymd_His");
        }
        if (is_array($log_data) || is_object($log_data)) {
            $output = json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } else {
            $output = strval($log_data);
        }
        if (!file_exists($log_path)) {
            @mkdir($log_path, 0775, true);
        }
        $log_path .= date("Y-m-d") . "/";
        if (!file_exists($log_path)) {
            @mkdir($log_path, 0775, true);
        }
        file_put_contents($log_path . $date_ymh_his . "_$log_name.log", $output, FILE_APPEND);
    }