<?

    /**
     * Базовый класс для всех таблиц а-ля ORM, разработанный Intop для максимально быстрой работы сайтов, из минусов - классы нужно писать руками
     */
    abstract class AbstractTable
    {
        const CACHE = true;

        public ?string
            $id = null,
            $short = null;

        private ?string
            $date_insert = null,
            $date_update = null;

        /**
         * @return DateTime|null
         * @throws DateMalformedStringException
         */
        public function get_date_insert(): ?DateTime
        {
            return new DateTime($this->date_insert);
        }

        /**
         * @return DateTime|null
         * @throws DateMalformedStringException
         */
        public function get_date_update(): ?DateTime
        {
            return new DateTime($this->date_update);
        }

        /**
         * @return string|null
         * @throws DateMalformedStringException
         */
        public function get_date_insert_display(): ?string
        {
            $date_insert = $this->get_date_insert(); 
            return datetime_format($date_insert);
        }

        /**
         * @return string|null
         * @throws DateMalformedStringException
         */
        public function get_date_update_display(): ?string
        {
            $date_update = $this->get_date_update();
            return datetime_format($date_update);
        }

        /**
         * @return string
         */
        public static function get_class_name(): string
        {
            return get_called_class();
        }

        /**
         * SQL запрос с данными для пользователей
         * Запрос должен обязательно заканчиваться блоком WHERE, так как к запросу будут добавляться доп блоки WHERE из фильтра и ORDER BY из функции get_order_by
         * @return string
         */
        static function get_public_query(): string
        {
            return "SELECT * FROM " . static::get_table_name() . " WHERE (1 = 1)";
        }

        /**
         * Исходное название таблицы
         * @return string
         */
        abstract static function get_table_name(): string;

        /**
         * Сортировка для таблицы
         * @return array
         */
        static function get_order_by(): array
        {
            $table_name = static::get_table_name();
            return ["$table_name.ID_$table_name"];
        }

        /**
         * AbstractTable constructor
         * @param array $row
         * @throws Exception
         */
        function __construct(array $row = [])
        {
            if (!empty($row)) {
                $this->id = $row["ID_" . static::get_table_name()];
                if (!empty($row[static::get_table_name() . "_SHORT"])) {
                    $this->short = $row[static::get_table_name() . "_SHORT"];
                }
                if (!empty(static::get_table_name() . "_DATE_INSERT")) {
                    $this->date_insert = $row[static::get_table_name() . "_DATE_INSERT"];
                }
                if (!empty(static::get_table_name() . "_DATE_UPDATE")) {
                    $this->date_update = $row[static::get_table_name() . "_DATE_UPDATE"];
                }
            }
        }

        /**
         * Получает одну запись из таблицы по запросу
         * @param string $query
         * @return static|null
         * @throws Exception
         */
        public static function get_one_by_query(string $query): ?static
        {
            if (static::CACHE === true) {
                $row = db_cache_query_one($query);
            } else {
                $res = db_query($query);
                if ($res && db_num_rows($res)) {
                    $row = db_fetch_assoc($res);
                }
            }
            if (!empty($row)) {
                return new static($row);
            } else {
                return null;
            }
        }

        /**
         * Получает все записи из таблицы по запросу. !! В запрос необходимо добавить get_order_by()
         * @param string $query
         * @return static[]
         * @throws Exception
         */
        public static function get_all_by_query(string $query): array
        {
            $table_name = static::get_table_name();
            $return = [];
            if (static::CACHE === true) {
                $res = db_cache_query_result($query);
                if ($res) {
                    foreach ($res as $row) {
                        $return[$row["ID_$table_name"]] = new static($row);
                    }
                }
            } else {
                $res = db_query($query);
                if ($res && db_num_rows($res)) {
                    while ($row = db_fetch_assoc($res)) {
                        $return[$row["ID_$table_name"]] = new static($row);
                    }
                }
            }
            return $return;
        }

        /**
         * @param string|null $id
         * @return static|null
         * @throws Exception
         */
        public static function get_by_id(?string $id = null): ?static
        {
            $table_name = static::get_table_name();
            $query = static::get_public_query() . " AND ($table_name.ID_$table_name = '$id')";
            $query .= " LIMIT 1";
            return static::get_one_by_query($query);
        }

        /**
         * @param int[] $id_array
         * @return static[]
         * @throws Exception
         */
        public static function get_by_id_array(?array $id_array = null): array
        {
            if (empty($id_array)) {
                return [];
            }
            $table_name = static::get_table_name();
            $db_ids = implode("', '", $id_array);
            $query = static::get_public_query() . " AND ($table_name.ID_$table_name IN ('$db_ids'))";
            $query .= " ORDER BY " . implode(",", static::get_order_by());
            return static::get_all_by_query($query);
        }

        /**
         * @param string|null $eng
         * @param string|null $table_name
         * @return static|null
         * @throws Exception
         */
        public static function get_by_eng(?string $eng = null, ?string $table_name = null): ?static
        {
            if (empty($table_name)) {
                $table_name = static::get_table_name();
            }
            $query = static::get_public_query() . " AND ($table_name.{$table_name}_ENG = '$eng')";
            $query .= " LIMIT 1";
            return static::get_one_by_query($query);
        }

        /**
         * @param int[] $eng_array
         * @return static[]
         * @throws Exception
         */
        public static function get_by_eng_array(?array $eng_array = null): array
        {
            if (empty($eng_array)) {
                return [];
            }
            $table_name = static::get_table_name();
            $db_engs = implode("', '", $eng_array);
            $query = static::get_public_query() . " AND ($table_name.{$table_name}_ENG IN ('$db_engs'))";
            $query .= " ORDER BY " . implode(",", static::get_order_by());
            return static::get_all_by_query($query);
        }

        /**
         * @param string|null $short
         * @param string|null $table_name
         * @return static|null
         * @throws Exception
         */
        public static function get_by_short(?string $short = null, ?string $table_name = null): ?static
        {
            if (empty($table_name)) {
                $table_name = static::get_table_name();
            }
            $query = static::get_public_query() . " AND ($table_name.{$table_name}_SHORT = '$short')";
            $query .= " LIMIT 1";
            return static::get_one_by_query($query);
        }

        /**
         * @param int[] $short_array
         * @return static[]
         * @throws Exception
         */
        public static function get_by_short_array(?array $short_array = null): array
        {
            if (empty($short_array)) {
                return [];
            }
            $table_name = static::get_table_name();
            $db_shorts = implode("', '", $short_array);
            $query = static::get_public_query() . " AND ($table_name.{$table_name}_SHORT IN ('$db_shorts'))";
            $query .= " ORDER BY " . implode(",", static::get_order_by());
            return static::get_all_by_query($query);
        }

        /**
         * @param AbstractFilter[] $filters
         * @param array $order_by
         * @return static[]
         * @throws Exception
         */
        public static function get_all(array $filters = [], array $order_by = []): array
        {
            $query = static::get_public_query();
            foreach ($filters as $filter) {
                $query .= $filter->get_where();
            }
            if ($order_by) {
                $query .= " ORDER BY " . implode(",", $order_by);
            } else {
                $query .= " ORDER BY " . implode(",", static::get_order_by());
            }
            //echo $query . "\n<br><br>\n";
            return static::get_all_by_query($query);
        }

        /**
         * @param AbstractFilter[] $filters
         * @return int
         * @throws Exception
         */
        public static function get_count(array $filters = []): int
        {
            $table_name = static::get_table_name();
            $query = static::get_public_query();
            foreach ($filters as $filter) {
                $query .= $filter->get_where();
            }
            $query = preg_replace("/^\s*SELECT[^\n]+/iu", "SELECT $table_name.ID_$table_name", $query);
            $query = "SELECT COUNT(*) AS {$table_name}_COUNT FROM ($query) qc";
            // echo $query . "\n<br><br>\n";
            if (static::CACHE === true) {
                $row = db_cache_query_one($query);
            } else {
                $res = db_query($query);
                if ($res && db_num_rows($res)) {
                    $row = db_fetch_assoc($res);
                }
            }
            if (!empty($row)) {
                return $row["{$table_name}_COUNT"];
            } else {
                return 0;
            }
        }

        /**
         * @param int $limit
         * @param int $offset
         * @param AbstractFilter[] $filters
         * @param array $order_by
         * @return static[]
         * @throws Exception
         */
        public static function get_by_limit(int $limit, int $offset = 0, array $filters = [], array $order_by = []): array
        {
            $query = static::get_public_query();
            foreach ($filters as $filter) {
                $query .= $filter->get_where();
            }
            if ($order_by) {
                $query .= " ORDER BY " . implode(",", $order_by) . " LIMIT $offset, $limit";
            } else {
                $query .= " ORDER BY " . implode(",", static::get_order_by()) . " LIMIT $offset, $limit";
            }
            // if (!empty($_GET["xd"])) {
            //    echo "<pre>$query</pre><br>";
            //    die();
            // }
            return static::get_all_by_query($query);
        }

        /**
         * @param AbstractFilter[] $filters
         * @param array $order_by
         * @return static|null
         * @throws Exception
         */
        public static function get_one(array $filters = [], array $order_by = []): ?static
        {
            $query = static::get_public_query();
            foreach ($filters as $filter) {
                $query .= $filter->get_where();
            }
            if ($order_by) {
                $query .= " ORDER BY " . implode(",", $order_by);
            } else {
                $query .= " ORDER BY " . implode(",", static::get_order_by());
            }
            $query .= " LIMIT 1";
            return static::get_one_by_query($query);
        }
    }
