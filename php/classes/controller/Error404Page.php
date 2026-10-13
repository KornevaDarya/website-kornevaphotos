<?

    /**
     * Класс данных для страницы ошибки 404
     */
    class Error404Page extends Page
    {
        /**
         * @return Error404Page
         * @throws Exception
         */
        public static function code404(): Error404Page
        {
            return new Error404Page(404, "404");
        }

        /**
         * Заполняем данные и возвращаем класс SEO
         * @return Seo
         * @throws Exception
         */
        function fill_seo(): Seo
        {
            return new Seo("Ошибка 404", "Ошибка 404");
        }
    }
