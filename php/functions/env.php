<?

    const
        ENV_MODE_LOCAL = "local",
        ENV_MODE_DEV = "dev",
        ENV_MODE_PROD = "prod";

    /**
     * Проверяем - включено ли кеширование
     * @return bool
     * @author Rushaker
     */
    function is_only_admin(): bool
    {
        return !empty($_ENV["ONLY_ADMIN"]) && ($_ENV["ONLY_ADMIN"] == "yes");
    }

    /**
     * Режима работы сайта
     * @return string
     */
    function env_mode(): string
    {
        if (
            !empty($_ENV["MODE"]) &&
            in_array($_ENV["MODE"], [ENV_MODE_LOCAL, ENV_MODE_DEV, ENV_MODE_PROD])
        ) {
            return $_ENV["MODE"];
        }

        return ENV_MODE_PROD;
    }