<?

    /**
     * Class AbstractFilter
     */
    abstract class AbstractFilter
    {
        /**
         * Получает условия AND для where части запроса
         * @return string
         */
        abstract function get_where(): string;


        /**
         * @param AbstractTable[] $results
         * @param string $table_name
         * @return string
         */
        protected static function add_result_where(array $results, string $table_name): string
        {
            if ($results) {
                $has_null = false;
                $result_str = "";
                foreach ($results as $result) {
                    if (!empty($result) && !empty($result->id)) {
                        if (!empty($result_str)) {
                            $result_str .= ", ";
                        }
                        $result_str .= $result->id;
                    } elseif ($result === null) {
                        $has_null = true;
                    }
                }
                if (($has_null === true) && !empty($result_str)) {
                    return " AND (($table_name.ID_$table_name IN ($result_str)) OR ($table_name.ID_$table_name IS NULL))";
                } elseif (!empty($result_str)) {
                    return " AND ($table_name.ID_$table_name IN ($result_str))";
                } elseif ($has_null === true) {
                    return " AND ($table_name.ID_$table_name IS NULL)";
                }
            }
            return "";
        }
    }