<?php

namespace App\Services;

use App\Contacts\ParserInterface;
use App\Services\Parsers\CsvParser;
use App\Services\Parsers\JsonParser;
use App\Services\Parsers\XmlParser;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class ParserFactory
{
    public static function make(UploadedFile $file): ParserInterface
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'csv' => new CsvParser($file),
            'xml' => new XmlParser($file),
            'json' => new JsonParser($file),
            default => throw new InvalidArgumentException("Nieobsługiwany format pliku: {$extension}"),
        };
    }
}
