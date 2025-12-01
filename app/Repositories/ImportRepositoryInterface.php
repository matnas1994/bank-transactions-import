<?php

namespace App\Repositories;

use App\Models\Import;

interface ImportRepositoryInterface
{
    public function createImport(array $importData, array $logs = []): Import;
}
