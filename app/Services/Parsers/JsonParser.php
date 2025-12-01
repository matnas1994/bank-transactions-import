<?php

namespace App\Services\Parsers;

use App\Contacts\ParserInterface;

class JsonParser extends AbstractParser
{
    public function parse(): array
    {
        $content = $this->file->get();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($data)) {
            throw new \InvalidArgumentException('Niepoprawny format JSON');
        }

        return $data;
    }
}
