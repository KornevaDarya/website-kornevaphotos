<?

    /**
     * Класс данных для главной страницы
     */
    class SimplePage extends Page
    {
        /**
         * @param Url $url
         * @return SimplePage
         * @throws Exception
         */
        public static function code200(Url $url): SimplePage
        {
            return new SimplePage(200, $url->lvl1);
        }

        /**
         * Заполняем данные и возвращаем класс SEO
         * @return Seo
         * @throws Exception
         */
        function fill_seo(): Seo
        {
            return new Seo();
        }
    }
