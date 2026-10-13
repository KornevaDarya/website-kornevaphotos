<?

    /**
     * Проверяем - включено ли кеширование
     * @return bool
     * @author Rushaker
     */
    function is_env_prod(): bool
    {
        return empty($_ENV["MODE"]) || ($_ENV["MODE"] == "prod");
    }

    /**
     * @return Smarty
     */
    function init_smarty(): Smarty
    {
        $smarty = new Smarty;
        $smarty->setCompileDir(ROOT . "/cache/templates")->setTemplateDir(ROOT . "/views");
        /*if ($_ENV["MODE"] === "prod") {
            $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        }*/
        return $smarty;
    }

    /**
     * @param string $path
     * @return string
     */
    function file_content(string $path): string
    {
        if (empty($path)) {
            return "";
        }

        $full_path = ROOT . "/{$path}";

        if (!is_file($full_path)) {
            return "";
        }

        return file_get_contents($full_path);
    }

    /**
     * @param string $folder
     * @param string $name
     * @return string
     */
    function actual_bundle_path(string $folder, string $name): string
    {
        $files_with_timestamp = array_map(function($file_path) {
            return [
                "path" => $file_path,
                "timestamp" => filectime($file_path)
            ];
        }, glob("{$folder}/*") ?: []);

        $target_files = array_filter($files_with_timestamp, function($file) use ($name) {
            return preg_match("/{$name}(\.[0-9a-f]{8})?\.(css|js)$/", $file["path"]);
        });

        if ($target_files) {
            usort($target_files, function ($a, $b) {
                return $b["timestamp"] - $a["timestamp"];
            });

            return $target_files[0]["path"];
        } else {
            return "";
        }
    }

    /**
     * @param string $name
     * @return string
     */
    function styles_by_mode(string $name): string
    {
        if ($_ENV["MODE"] === "dev") {
            return external_styles(actual_bundle_path("dist/css/dev", $name));
        } else {
            return inline_styles(actual_bundle_path("dist/css/prod", $name));
        }
    }

    /**
     * @param string $path
     * @return string
     */
    function external_styles(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            return "<link rel='stylesheet' href='/{$path}'>";
        }

        return "";
    }

    /**
     * @param string $path
     * @return string
     */
    function inline_styles(string $path): string
    {
        if (empty($path) || !file_exists(ROOT . "/{$path}")) {
            return "";
        }

        $content = file_content($path);
        return $content ? "<style>{$content}</style>" : "";
    }

    /**
     * @param string $path
     * @return string
     */
    function external_scripts(string $path): string
    {
        if (empty($path) || $path === '/') {
            return "";
        }
        
        if (file_exists(ROOT . "/{$path}")) {
            return "<script src='/{$path}'></script>";
        }

        return "";
    }

    /**
     * @param string $path
     * @return string
     */
    function inline_scripts(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            $content = file_content($path);
            return "<script>{$content}</script>";
        }

        return "";
    }

    /**
     * Получаем секретный ключ для передачи ajax запросов
     * @return string
     */
    function get_secret_key(): string
    {
        //$result = "";
        $result = date("Y-m-d", strtotime("-4 hours"));
        if (isset($_SERVER["HTTP_HOST"]))
            $result .= md5($_SERVER["HTTP_HOST"]);
        if (isset($_SERVER["HTTP_USER_AGENT"]))
            $result .= md5($_SERVER["HTTP_USER_AGENT"]);
        return md5(base64_encode($result) . "xdd(");
    }