<?
    /**
     * Читает данные из json-файла в папке /data
     * @param string $name имя файла без расширения
     * @return array пустой массив, если файла нет или json битый
     */
    function json_data(string $name): array
    {
        $path = ROOT . "/data/$name.json";

        if (!is_file($path)) {
            return [];
        }

        $data = json_decode(file_get_contents($path), true);

        return is_array($data) ? $data : [];
    }
