<?

    /**
     * Класс данных для страницы
     */
    abstract class Page
    {
        public int $code;
        public Seo $seo;
        public string
            $eng,
            $scripts,
            $styles,
            $view;

        /**
         * @param int $code
         * @param string $eng
         * @throws Exception
         */
        public function __construct(int $code, string $eng)
        {
            $this->eng = $eng;
            $this->code = $code;
            $this->scripts = external_scripts(actual_bundle_path("dist/js", $eng));
            $this->styles = styles_by_mode($eng);
            $this->view = "pages/$eng.tpl";
            $this->seo = $this->fill_seo();
        }

        /**
         * Возвращает код ответа и основной файл верстки страницы
         * @return Page
         * @throws Exception
         */
        public static function get_page(): Page
        {
            $url = new Url();

            try {
                // Страница не найдена
                if ($url->lvl1 == "404") {
                    return Error404Page::code404();
                } elseif (!$url->lvl1) {
                    return HomePage::code200();
                } elseif (file_exists(ROOT . "/views/pages/{$url->lvl1}.tpl")) {
                    return SimplePage::code200($url);
                } else {
                    // Страница не найдена
                    return Error404Page::code404();
                }
            } catch (Exception $e) {
                return Error415Page::code415();
            }
        }

        /**
         * Заполняем данные и возвращаем класс SEO
         * @return Seo
         * @throws Exception
         */
        abstract function fill_seo(): Seo;
    }
