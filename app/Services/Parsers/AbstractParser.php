<?php

namespace App\Services\Parsers;

use App\Contacts\ParserInterface;
use Illuminate\Http\UploadedFile;

abstract class AbstractParser implements ParserInterface
{
    public function __construct(protected UploadedFile $file) {}

    // Każdy parser musi zaimplementować własną logikę parse
    abstract public function parse(): array;
}
