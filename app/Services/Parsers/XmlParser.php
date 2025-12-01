<?php

namespace App\Services\Parsers;

use App\Contacts\ParserInterface;

class XmlParser extends AbstractParser
{
    public function parse(): array
    {
        $content = $this->file->get();

        // Wczytanie XML
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOERROR | LIBXML_ERR_NONE);
        if ($xml === false) {
            throw new InvalidArgumentException('Niepoprawny format XML');
        }

        // Konwersja na tablicę
        $rows = [];
        foreach ($xml->children() as $item) {
            $rows[] = json_decode(json_encode($item), true);
        }

        return $rows;
    }
}
