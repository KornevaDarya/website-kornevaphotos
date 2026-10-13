<?

    /**
     * Класс определения источника
     */
    class Referer
    {
        /**
         * UTM метки переработанные
         * @var UTM
         */
        public UTM $utm_intop;
        /**
         * UTM метки оригинальные
         * @var UTM
         */
        public UTM $utm_original;
        /**
         * Источник перехода
         * @var string
         */
        public string $referer = "";
        /**
         * Тип источника
         * @var string
         */
        public string $ref_type = "unknown";

        /**
         * Referer constructor
         */
        function __construct()
        {
            $this->utm_intop = new UTM();
            $this->utm_original = new UTM();

            if (!empty($_GET["utm_campaign"]) || !empty($_GET["utm_source"])) {
                $this->convert_input($_GET);
            } elseif (!empty($_SESSION["ref_type"])) {
                $this->convert_stored($_SESSION);
            } elseif (!empty($_COOKIE["ref_type"])) {
                $this->convert_stored($_COOKIE);
            }

            // Если мы узнали источник, то запишем его
            if (($this->utm_intop->utm_campaign != "") || ($this->utm_intop->utm_source != "")) {
                $this->store_source();
            } else {
                $this->convert_seo();
            }
        }

        /**
         * Присваиваем referer и все метки из сохраненного массива
         * @param array $source
         */
        private function convert_stored(array $source): void
        {
            $this->ref_type = $source["ref_type"];
            if (isset($source["referer"])) {
                $this->referer = $source["referer"];
            }
            if (isset($source["utm_campaign"])) {
                $this->utm_intop->utm_campaign = $source["utm_campaign"];
            }
            if (isset($source["utm_source"])) {
                $this->utm_intop->utm_source = $source["utm_source"];
            }
            if (isset($source["utm_medium"])) {
                $this->utm_intop->utm_medium = $source["utm_medium"];
            }
            if (isset($source["utm_term"])) {
                $this->utm_intop->utm_term = $source["utm_term"];
            }
            if (isset($source["utm_content"])) {
                $this->utm_intop->utm_content = $source["utm_content"];
            }
            if (isset($source["utm_campaign2"])) {
                $this->utm_original->utm_campaign = $source["utm_campaign2"];
            }
            if (isset($source["utm_source2"])) {
                $this->utm_original->utm_source = $source["utm_source2"];
            }
            if (isset($source["utm_medium2"])) {
                $this->utm_original->utm_medium = $source["utm_medium2"];
            }
            if (isset($source["utm_term2"])) {
                $this->utm_original->utm_term = $source["utm_term2"];
            }
            if (isset($source["utm_content2"])) {
                $this->utm_original->utm_content = $source["utm_content2"];
            }
        }

        /**
         * Присваиваем referer и все метки из get параметров
         */
        public function convert_input(?array $data): void
        {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->referer = $_SERVER["HTTP_REFERER"];
            }
            if (!empty($data)) {
                if (isset($data["utm_campaign"])) {
                    $this->utm_intop->utm_campaign = trim(strtolower($data["utm_campaign"]));
                    $this->utm_original->utm_campaign = trim(strtolower($data["utm_campaign"]));
                }
                if (isset($data["utm_source"])) {
                    $this->utm_intop->utm_source = trim(strtolower($data["utm_source"]));
                    $this->utm_original->utm_source = trim(strtolower($data["utm_source"]));
                }
                if (isset($data["utm_medium"])) {
                    $this->utm_intop->utm_medium = $data["utm_medium"];
                    $this->utm_original->utm_medium = $data["utm_medium"];
                }
                if (isset($data["utm_term"])) {
                    $this->utm_intop->utm_term = $data["utm_term"];
                    $this->utm_original->utm_term = $data["utm_term"];
                }
                if (isset($data["utm_content"])) {
                    $this->utm_intop->utm_content = $data["utm_content"];
                    $this->utm_original->utm_content = $data["utm_content"];
                }
            }

            if (in_array($this->utm_original->utm_campaign, ["v1", "v2", "v3", "v4", "intop-v1", "intop-v2", "intop-v3", "intop-v4", "v1-krd", "v2-krd", "v3-krd", "v4-krd", "v4-rus", "v4-msc", "v4-mir", "intop-v1-krd", "intop-v2-krd", "intop-v3-krd", "intop-v3.2-krd", "intop-v4-krd", "intop-v1-rus", "intop-v2-rus", "intop-v3-rus", "intop-v3.2-rus", "intop-v4-rus", "intop-v4-msc", "intop-v4-mir", "v5", "intop-v5", "v5-krd", "v5-rus", "v5-msc", "v5-mir", "intop-v5-krd", "intop-v5-rus", "intop-v5-msc", "intop-v5-mir", "v6", "intop-v6", "v6-krd", "v6-rus", "intop-v6-krd", "intop-v6-rus", "v7", "intop-v7", "v7-krd", "v7-reg", "v7-rus", "intop-v7-krd", "intop-v7-reg", "intop-v7-rus", "v8", "intop-v8", "v8-krd", "v8-reg", "v8-rus", "intop-v8-krd", "intop-v8-reg", "intop-v8-rus", "intop-v8-mir",

                "v9", "intop-v9", "v9-krd", "v9-reg", "v9-rus", "intop-v9-krd", "intop-v9-reg", "intop-v9-rus", "intop-v9-mir"
            ])) {

                $this->utm_intop->utm_source = "Интернет-реклама";

                switch ($this->utm_original->utm_source) {
                    case "yandex":
                    case "yd":
                    case "yd-v-parke":
                    case "yd-v-lesu":
                    case "yd-na-nabejnoy":
                        $this->utm_intop->utm_source = "Яндекс.Директ Поиск";
                        break;
                    case "yd-master":
                    case "yd-master-obrivnaya":
                    case "yd-master-nemeckaya":
                        $this->utm_intop->utm_source = "Яндекс.Директ Мастер Кампаний";
                        break;
                    case "rsya":
                    case "rsya-text":
                        $this->utm_intop->utm_source = "Яндекс.Директ РСЯ Текстовые";
                        break;
                    case "rsya-img":
                        $this->utm_intop->utm_source = "Яндекс.Директ РСЯ Графические";
                        break;
                    case "retarg-konkurent":
                        $this->utm_intop->utm_source = "Яндекс.Директ Ретаргетинг Конкурентов";
                        break;
                    case "retarg-dostig":
                        $this->utm_intop->utm_source = "Яндекс.Директ Ретаргетинг по Целям";
                        break;
                    case "retarg-classic":
                        $this->utm_intop->utm_source = "Яндекс.Директ Ретаргетинг по Посещениям";
                        break;
                    case "retarg-office":
                        $this->utm_intop->utm_source = "Яндекс.Директ Ретаргетинг по Офисам застройщиков";
                        break;
                    case "yd-main":
                        $this->utm_intop->utm_source = "Яндекс.Директ Медийная на главной";
                        break;
                    case "yd-search-banner":
                        $this->utm_intop->utm_source = "Яндекс.Директ Медийный баннер на поиске";
                        break;
                    case "google":
                    case "ga":
                        $this->utm_intop->utm_source = "Google Ads Поиск";
                        break;
                    case "kms":
                    case "kms-interes":
                        $this->utm_intop->utm_source = "Google КМС по интересам";
                        break;
                    case "vk":
                    case "vkontakte":
                        $this->utm_intop->utm_source = "ВКонтакте";
                        break;
                    case "facebook":
                    case "fb":
                        $this->utm_intop->utm_source = "Facebook";
                        break;
                    case "targetmailru-multi":
                    case "mytarget":
                    case "mytarget-multi":
                    case "targetmailru":
                        $this->utm_intop->utm_source = "Mail.ru Таргет Мультиформатное";
                        break;
                    case "mytarget-banner":
                        $this->utm_intop->utm_source = "Mail.ru Таргет Баннер";
                        break;
                    case "mytarget-tizer":
                        $this->utm_intop->utm_source = "Mail.ru Таргет Тизер";
                        break;
                    case "inst":
                    case "insta":
                    case "instagram":
                        $this->utm_intop->utm_source = "Instagram";
                        break;
                    case "roistat_sms":
                    case "intop_sms":
                        $this->utm_intop->utm_source = "SMS рассылка";
                        break;
                    case "posev":
                    case "intop_posev_krd":
                    case "intop_posev_rus":
                        $this->utm_intop->utm_source = "Посевы";
                        break;
                    case "intop_telegram":
                        $this->utm_intop->utm_source = "Telegram";
                        break;
                    case "intop_dzen":
                        $this->utm_intop->utm_source = "Яндекс.Дзен";
                        break;
                }

                if (preg_match("/.+-rus$/", $this->utm_original->utm_campaign)) {
                    $this->ref_type = "rus";
                    $this->utm_intop->utm_campaign = "Реклама по России";
                } elseif (preg_match("/.+-reg$/", $this->utm_original->utm_campaign)) {
                    $this->ref_type = "reg";
                    $this->utm_intop->utm_campaign = "Реклама по Регионам";
                } elseif (preg_match("/.+-reg$/", $this->utm_original->utm_campaign)) {
                    $this->ref_type = "msc";
                    $this->utm_intop->utm_campaign = "Реклама по Москве";
                } elseif (preg_match("/.+-mir$/", $this->utm_original->utm_campaign)) {
                    $this->ref_type = "regions";
                    $this->utm_intop->utm_campaign = "Реклама по Миру";
                } else {
                    $this->ref_type = "krd";
                    $this->utm_intop->utm_campaign = "Реклама по Краснодарскому краю";
                }
            }
        }

        /**
         * Запоминаем все источники
         */
        public function store_source(): void
        {
            SetCookie("ref_type", $this->ref_type, time() + (60 * 60 * 24 * 14));
            $_SESSION["ref_type"] = $this->ref_type;
            SetCookie("utm_campaign", $this->utm_intop->utm_campaign, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_campaign"] = $this->utm_intop->utm_campaign;
            SetCookie("utm_source", $this->utm_intop->utm_source, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_source"] = $this->utm_intop->utm_source;
            SetCookie("utm_medium", $this->utm_intop->utm_medium, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_medium"] = $this->utm_intop->utm_medium;
            SetCookie("utm_term", $this->utm_intop->utm_term, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_term"] = $this->utm_intop->utm_term;
            SetCookie("utm_content", $this->utm_intop->utm_content, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_content"] = $this->utm_intop->utm_content;
            SetCookie("utm_campaign2", $this->utm_original->utm_campaign, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_campaign2"] = $this->utm_original->utm_campaign;
            SetCookie("utm_source2", $this->utm_original->utm_source, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_source2"] = $this->utm_original->utm_source;
            SetCookie("utm_medium2", $this->utm_original->utm_medium, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_medium2"] = $this->utm_original->utm_medium;
            SetCookie("utm_term2", $this->utm_original->utm_term, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_term2"] = $this->utm_original->utm_term;
            SetCookie("utm_content2", $this->utm_original->utm_content, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_content2"] = $this->utm_original->utm_content;
            SetCookie("referer", $this->referer, time() + (60 * 60 * 24 * 14));
            $_SESSION["referer"] = $this->referer;
        }

        /**
         * Присваиваем referer и все метки из SEO
         */
        private function convert_seo(): void
        {
            $this->utm_intop->utm_source = "SEO/другое";
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->referer = $_SERVER["HTTP_REFERER"];
            }
            if (stristr($this->referer, "avito.ru")) {
                $this->utm_intop->utm_source = "Avito";
            }
            if (stristr($this->referer, "cian.ru")) {
                $this->utm_intop->utm_source = "Cian";
            }
            if (stristr($this->referer, "domclick.ru")) {
                $this->utm_intop->utm_source = "ДомКлик";
            }
            if (stristr($this->referer, "instagram.com")) {
                $this->utm_intop->utm_source = "Instagram";
            }
            if (stristr($this->referer, "facebook.com")) {
                $this->utm_intop->utm_source = "Facebook";
            }
            if (stristr($this->referer, "vk.com")) {
                $this->utm_intop->utm_source = "ВКонтакте";
            }
            if (stristr($this->referer, "yandex.ru")) {
                $this->utm_intop->utm_source = "Yandex SEO";
            }
            if (stristr($this->referer, "google.ru")) {
                $this->utm_intop->utm_source = "Google SEO";
            }
            if (stristr($this->referer, "google.com")) {
                $this->utm_intop->utm_source = "Google SEO";
            }
            if (stristr($this->referer, "mail.ru")) {
                $this->utm_intop->utm_source = "Mail.Ru";
            }
            if (stristr($this->referer, "bing.com")) {
                $this->utm_intop->utm_source = "Bing";
            }
            if (stristr($this->referer, "2gis.ru")) {
                $this->utm_intop->utm_source = "2GIS";
            }
        }
    }
