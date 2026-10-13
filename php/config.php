<?
    /**
     * Константы проекта.
     * Здесь только несекретные данные сайта — ключи и токены живут в .env
     * Подключается из php/common.php после загрузки .env
     */

    const
        // Контакты, выводятся в шаблонах
        SITE_PHONE = "+79189998587",
        SITE_EMAIL = "tk.elisey.ug@gmail.com",
        SITE_ADDRESS1 = "",
        SITE_ADDRESS2 = "",
        // Город и индекс для микроразметки Schema.org
        SITE_LOCALITY = "",
        SITE_POSTAL_CODE = "";

    /**
     * Имя сайта для писем и CRM: из .env, иначе из домена запроса
     * @return string
     */
    function site_name(): string
    {
        $from_env = trim((string)($_ENV["SITE_NAME"] ?? ""));

        if ($from_env !== "") {
            return $from_env;
        }

        return normalize_domain($_SERVER["SERVER_NAME"] ?? "");
    }

    /**
     * Значение переменной окружения в виде строки
     * @param string $name
     * @return string
     */
    function env_str(string $name): string
    {
        return trim((string)($_ENV[$name] ?? ""));
    }
