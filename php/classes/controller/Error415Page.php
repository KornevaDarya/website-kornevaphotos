<?

    /**
     * Класс данных для страницы ошибки 415
     */
    class Error415Page extends Page
    {
        /**
         * @return Error415Page
         * @throws Exception
         */
        public static function code415(): Error415Page
        {
            return new Error415Page(415, "415");
        }

        /**
         * Заполняем данные и возвращаем класс SEO
         * @return Seo
         * @throws Exception
         */
        function fill_seo(): Seo
        {
            return new Seo("Ошибка 415", "Ошибка 415");
        }
    }
