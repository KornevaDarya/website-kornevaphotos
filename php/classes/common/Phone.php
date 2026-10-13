<?

    /**
     * Класс телефона
     */
    class Phone
    {
        /**
         * Телефон для отображения
         * @var string
         */
        public string $title;
        /**
         * Телефон для ссылок href
         * @var string
         */
        public string $link;

        /**
         * Функция преобразовывает телефон в ссылку для верстки
         * @param string $phone_in
         * @return string
         */
        protected function phone_to_href(string $phone_in): string
        {
            return "tel:+" . normalize_phone($phone_in, true);
        }

        /**
         * Phone constructor
         * @param string $phone
         */
        function __construct(string $phone)
        {
            // Телефон ещё не задан (см. php/config.php) — не выводим пустую заготовку
            if (normalize_phone($phone) === "") {
                $this->title = "";
                $this->link = "";
                return;
            }

            $this->title = format_phone($phone);
            $this->link = $this->phone_to_href($phone);
        }
    }
