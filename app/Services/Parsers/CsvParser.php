<?php

namespace App\Services\Parsers;

class CsvParser extends AbstractParser
{
    public function parse(): array
    {
        $content = $this->file->get();
        $lines = array_filter(explode(PHP_EOL, $content));

        $rows = [];
        $headers = [];

        foreach ($lines as $index => $line) {
            $data = str_getcsv($line);

            if ($index === 0) {
                $headers = $data;
                continue;
            }

            $rows[] = array_combine($headers, $data);
        }

        return $rows;
    }
}
