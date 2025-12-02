<?php

namespace App\Repositories;

use App\Models\Import;

class ImportRepository implements ImportRepositoryInterface
{
    public function createImport(array $importData, array $logs = []): Import
    {
        $import = Import::create($importData);

        if (! empty($logs)) {
            $import->logs()->createMany($logs);
        }

        return $import->fresh('logs');
    }
}
