<?

    /**
     * Класс данных для главной страницы
     */
    class HomePage extends Page
    {
        /**
         * @return HomePage
         * @throws Exception
         */
        public static function code200(): HomePage
        {
            return new HomePage(200, "home");
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
