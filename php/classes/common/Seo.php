<?

    /**
     * Класс Seo страниц
     */

    class Seo
    {

        const
            DEFAULT_SHORT = "",
            DEFAULT_TITLE = "",
            DEFAULT_DESCRIPTION = "",
            DEFAULT_KEYWORDS = "",
            DEFAULT_IMAGE = "/apple-touch-icon.png";

        public ?string
            $short = null,
            $meta_title = null,
            $meta_desc = null,
            $meta_keywords = null,
            $og_image = null;

        /**
         * Seo constructor
         * @param string|null $short
         * @param string|null $meta_title
         * @param string|null $meta_desc
         * @param string|null $meta_keywords
         * @param string|null $og_image
         */
        public function __construct(?string $short = null, ?string $meta_title = null, ?string $meta_desc = null, ?string $meta_keywords = null, ?string $og_image = null)
        {
            $this->short = $short ?: self::DEFAULT_SHORT;
            $this->meta_title = $meta_title ?: self::DEFAULT_TITLE;
            $this->meta_desc = $meta_desc ?: self::DEFAULT_DESCRIPTION;
            $this->meta_keywords = $meta_keywords ?: self::DEFAULT_KEYWORDS;
            $this->og_image = $og_image ?: self::DEFAULT_IMAGE;
        }
    }
