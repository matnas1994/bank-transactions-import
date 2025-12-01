<?php

namespace App\Contacts;

use App\Models\Import;
use Illuminate\Http\UploadedFile;

interface ImportContact
{
    public function processFile(UploadedFile $file): Import;
}
