<?

    /**
     * Генерация случайной строки
     * @param int $length
     * @param false $only_numbers
     * @return string
     */
    function generate_random_string(int $length = 10, bool $only_numbers = false): string
    {
        $characters = "123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ()";
        if ($only_numbers) {
            $characters = "1234567890";
        }
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Возвращает число в виде строки без десятичных знаков и с пробелом перед тысячей/миллионом
     * @param float $number
     * @return string
     */
    function int_format(float $number): string
    {
        $true_int = round($number);
        return number_format($true_int, 0, ",", " ");
    }

    /**
     * Возвращает число в виде строки с 2мя десятичными знаками и с пробелом перед тысячей/миллионом
     * @param float|null $float $float
     * @param bool $trim_zeroes
     * @param int $decimals
     * @param string $thousands_separator
     * @return string
     */
    function float_format(?float $float, bool $trim_zeroes = false, int $decimals = 2, string $thousands_separator = " "): string
    {
        if ($float) {
            $float_format = number_format($float, $decimals, ",", $thousands_separator);
            if ($trim_zeroes) {
                $float_format = rtrim(rtrim($float_format, "0"), ",");
            }
        } else {
            $float_format = "0";
        }
        return $float_format;
    }

    /**
     * Возвращает число в виде строки со знаком рубля, с 2мя десятичными знаками и с пробелом перед тысячей/миллионом
     * @param float|null $float $float
     * @param bool $trim_zeroes
     * @return string
     */
    function currency_format(?float $float, bool $trim_zeroes = true): string
    {
        return float_format($float, $trim_zeroes) . " ₽";
    }

    /**
     * Получаем цифры из строки
     * @param string $string
     * @return string
     */
    function numbers_from_str(string $string): string
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    /**
     * @param string $phone_in
     * @param bool $is_first_8 если true — префикс "8" вместо "7"
     * @return string
     */
    function normalize_phone(string $phone_in, bool $is_first_8 = false): string
    {
        $prefix = $is_first_8 ? "8" : "7";

        $phone = numbers_from_str($phone_in);
        $uniq_count = count(array_unique(str_split($phone)));
        if ((strlen($phone) >= 10) && ($uniq_count > 2)) {
            if ((strlen($phone) == 11) && (substr($phone, 0, 1) == "8")) {
                $phone = $prefix . substr($phone, 1);
            } elseif (strlen($phone) == 10) {
                $phone = $prefix . $phone;
            }
        } else {
            $phone = "";
        }
        return $phone;
    }

    /**
     * Строка телефона в корректном формате
     * @param string $phone_in
     * @return string
     */
    function format_phone(string $phone_in): string
    {
        $phone = normalize_phone($phone_in);
        $prefix = "+7";
        $code = substr($phone, 1, 3);
        $part1 = substr($phone, 4, 3);
        $part2 = substr($phone, 7, 2);
        $part3 = substr($phone, 9, 2);

        return "$prefix ($code) $part1-$part2-$part3";
    }

    /**
     * Вспомогательная функция для нормализации домена из url
     * @param string $url
     * @return string
     */
    function url_to_domain_helper(string $url): string
    {
        $host = @parse_url($url, PHP_URL_HOST);
        // If the URL can't be parsed, use the original URL
        // Change to "return false" if you don't want that
        if (!$host) {
            return "";
        }
        // The "www." prefix isn't really needed if you're just using
        // this to display the domain to the user
        if (substr($host, 0, 4) == "www.") {
            $host = substr($host, 4);
        }
        // You might also want to limit the length if screen space is limited
        //if (strlen($host) > 50)
        //$host = substr($host, 0, 47) . '...';
        return $host;
    }

    /**
     * Нормализация домена из url
     * @param string $url
     * @return string
     */
    function normalize_domain(string $url): string
    {
        $domain = "";

        if ($url) {
            if (url_to_domain_helper($url)) {
                $domain = idn_to_utf8(url_to_domain_helper($url));
            } else {
                $domain = idn_to_utf8($url);
            }
        }

        return str_replace("xn--p1ai", "рф", str_replace("www.", "", $domain));
    }

    /**
     * Получаем ip адрес
     * @return mixed|string
     */
    function get_user_ip()
    {
        if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            if (strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ",") > 0) {
                $addr = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
                return trim($addr[0]);
            } else {
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    /**
     * @param string|null $text_in
     * @return string
     */
    function strip_seo(?string $text_in): string
    {
        if ($text_in) {
            $text = strip_tags($text_in);
            $text = str_replace("&nbsp;", " ", $text);
            $text = preg_replace('/"([^"]+)"/iu', "«$1»", $text);
            $text = str_replace('"', "", $text);
            $text = preg_replace("/\s+/iu", " ", $text);
            $text = trim($text);
        } else {
            $text = "";
        }
        return $text;
    }

    /**
     * Возвращает путь к webp изображения
     * @param string $img_path
     * @return string
     */
    function imgpath_to_webp(string $img_path): string
    {
        return preg_replace("/\.(jpe?g|png)$/", ".webp", $img_path);
    }

    /**
     * Преобразовывает строку изображения из базы в путь к фото
     * @param string|null $photo_urls
     * @return string
     */
    function db_photo_urls_to_one_img(?string $photo_urls): string
    {
        $return = "";
        if ($photo_urls && ($photo_urls[0] == "[")) {
            $images = json_decode($photo_urls, true);
            if ($images) {
                $return = $images[0];
            }
        }
        return $return;
    }
