<?php

if (!function_exists('parse_csv')) {
    /**
     * @param $fileName
     * @param array $requiredKeys
     * @return array|bool
     */
    function parse_csv($fileName, array $requiredKeys = []): array|bool
    {
        $file = new \SplFileObject($fileName);
        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::READ_AHEAD  |
            SplFileObject::DROP_NEW_LINE
        );

        $data = [
            'keys' => [],
            'rows' => [],
        ];

        foreach ($file as $row) {
            if ($file->key() == 0) {
                $data['keys'] = $row;
            } else {
                if (count($row) == count($data['keys'])) {
                    $data['rows'][] = array_combine($data['keys'], $row);
                } else {
                    return false;
                }
            }
        }

        if($data['keys'] !== $requiredKeys || count($data['rows']) < 1) {
            return false;
        }

        return $data;
    }
}

if (!function_exists('price_format')) {
    function price_format($price)
    {
        return number_format((float)$price, 2, '.', '');
    }
}
